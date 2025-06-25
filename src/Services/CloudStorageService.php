<?php

namespace DagaSmart\CloudStorage\Services;

use DagaSmart\BizAdmin\Admin;
use DagaSmart\CloudStorage\Models\Base;
use DagaSmart\CloudStorage\Models\CloudStorage;
use Dagasmart\BizAdmin\Services\AdminService;
use Illuminate\Database\Query\Builder;

/**
 * 存储设置
 *
 * @method CloudStorage getModel()
 * @method CloudStorage|Builder query()
 */
class CloudStorageService extends AdminService
{
    protected string $modelName = CloudStorage::class;

    public function saving(&$data, $primaryKey = ''): void
    {
        if (filled($data)) {
            $id = $data['id'] ?? null;

            if (isset($data['title'])) {

                $title = $data['title'] ?: null;
                admin_abort_if(!$title, '名称不能为空');

                $data = clear_array_trim($data); //消除空格

                //判断名称已存在
                $is_exists = $this->query()
                    ->where(['title' => $title])
                    ->when($id, function ($query) use (&$id) {
                        return $query->where('id', '!=', $id);
                    })
                    ->exists();
                admin_abort_if($is_exists, '名称已存在，请换个试试');
            }


            $data['description'] = $data['description'] ?? '';
            $data['extension'] = $data['extension'] ?? '';
        }
    }

    public function saved($model, $isEdit = false): void
    {
        if ($model->is_default == Base::ENABLE) {
            $this->query()->where('id', '!=', $model->id)->update(['is_default' => Base::FORBIDDEN]);
        }
    }

    public function getStorageOptions(): array
    {
        $data = $this->query()->where(['enabled' => 1])->get(['id', 'title'])->toArray();
        $res = [];
        foreach ($data as $datum) {
            $res[] = ['label' => $datum['title'], 'value' => $datum['id']];
        }
        if (Admin::currentModule()) {
            //默认追加本地存储
            $local = $this->query()
                ->withoutGlobalScope('ActionScope')
                ->where(['driver' => 'local'])
                ->where(['enabled' => 1])
                ->where(['is_default' => 1])
                ->whereNull('module')
                ->select(['id as value', 'title as label'])
                ->first();
            array_unshift($res, $local);
        }

        return $res;
    }

    /**
     * 查询数据
     *
     * @return array
     */
    public function list(): array
    {
        $keyword = request()->keyword;

        $isDefault = request()->is_default;

        $enabled = request()->enabled;

        $query = $this->query()
            ->when(! empty($keyword), function ($query) use ($keyword) {
                $query->where('title', 'like', "%{$keyword}%")->orWhere('description', 'like', "%{$keyword}%");
            })
            ->when(is_numeric($isDefault), function ($query) use ($isDefault) {
                $query->where('is_default', $isDefault);
            })
            ->when(is_numeric($enabled), function ($query) use ($enabled) {
                $query->where('enabled', $enabled);
            });

        $items = (clone $query)->paginate(request()->input('perPage', 20))->items();
        foreach ($items as &$item) {
            if (filled($item)) {
                $driver_str = match ($item->driver) {
                    'local' => '本地存储',
                    'oss' => '阿里云OSS',
                    'cos' => '腾讯云COS',
                    'kodo' => '七牛云KODO',
                    default => '',
                };
                $item->setAttribute('driver_str', $driver_str);
            }
        }
        $total = (clone $query)->count();

        return compact('items', 'total');
    }
}
