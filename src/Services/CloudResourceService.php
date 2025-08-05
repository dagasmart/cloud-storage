<?php

namespace DagaSmart\CloudStorage\Services;

use DagaSmart\CloudStorage\CloudStorageServiceProvider;
use DagaSmart\CloudStorage\Models\CloudResource;
use Dagasmart\BizAdmin\Services\AdminService;
use Exception;
use Illuminate\Database\Query\Builder;

/**
 * 资源管理
 *
 * @method CloudResource getModel()
 * @method CloudResource|Builder query()
 */
class CloudResourceService extends AdminService
{
    protected string $modelName = CloudResource::class;

    const array fileType = [
        'all', 'image', 'document', 'video', 'audio', 'other',
    ];

    /**
     * @throws Exception
     */
    protected function saveData($data, array $columns, CloudResource $model): int
    {
        foreach ($data as $k => $v) {
            if (! in_array($k, $columns)) {
                continue;
            }
            $model->setAttribute($k, $v);
        }

        return $model->save();
    }

    /**
     * @throws Exception
     */
    public function getDefaultQuery($id = null): object
    {
        $cloudStorageService = new CloudUploadService;

        return $cloudStorageService->config($id);
    }

    /**
     * 查询数据
     *
     * @return array
     */
    public function list(): array
    {
        $keyword = request()->keyword;
        $storage_id = request()->storage_id;
        $isType = request()->is_type;
        $query = $this->query()
            ->when(!empty($keyword), function ($query) use ($keyword) {
                $query->where('title', 'like', "%{$keyword}%");
            })
            ->when(!empty($storage_id), function ($query) use ($storage_id) {
                $query->whereIn('storage_id', explode(',', $storage_id));
            })
            ->when(!empty($isType), function ($query) use ($isType) {
                $query->where('is_type', $isType);
            })
            ->orderBy('id', 'desc');

        $items = (clone $query)->paginate(request()->input('perPage', 40))->items();
        foreach ($items as &$item) {
            if (filled($item)) {
                $url = $item->url;
                $item->url = $url ? [
                    'path' => $url,
                    'value' => $item->getCloudStoragePath($url, $item->storage_id),
                ] : [];
            }
        }
        $total = (clone $query)->count();

        return ['items' => $items, 'total' => $total];
    }

    /**
     * 获取文件类型
     *
     * @throws Exception
     */
    public function getAccept($id = null)
    {
        return $this->getDefaultQuery($id)->accept ?? '*';
    }

    /**
     * @throws Exception
     */
    public function getSize($id = null)
    {
        return $this->getDefaultQuery($id)->file_size ?? 0;
    }

    /**
     * @throws Exception
     */
    public function getStorageId($id = null)
    {
        return $this->getDefaultQuery($id)->id ?? null;
    }

    /**
     * 生成icon
     *
     * @return array|array[]
     */
    public function generateIcon(): array
    {
        $list = [];
        foreach (self::fileType as $index => $item) {
            $list[$index]['id'] = $index;
            $list[$index]['label'] = cloud_storage_trans($item);
            $list[$index]['icon'] = $this->getIcon('/image/file-type/'.$item.'.png');
            $list[$index]['className'] = 'nav-icon-img';
            $list[$index]['to'] = admin_url('/cloud_storage/resource?page='.request()->page.'&perPage='.request()->perPage.'&is_type='.$index);
            $list[$index]['active'] = request()->is_type == $index;
        }

        return $list;
    }

    public function getReport(): array
    {
        $list = [];
        $i = 0;
        foreach (self::fileType as $index => $item) {
            if ($index > 0) {
                $list[$i]['value'] = $this->count($index);
                $list[$i]['name'] = cloud_storage_trans($item);
                $list[$i]['size'] = $this->getSizeMemory($index);
                $i++;
            }
        }

        return $list;
    }

    /**
     * 获取icon
     */
    public function getIcon(string $path): string
    {
        return CloudStorageServiceProvider::instance()->assetUrl($path);
    }

    public function getSizeMemory(int $isType = 0): ?string
    {
        $size = $this->sum('size', $isType);

        return $size ? formatBytes($size) : '0';
    }

    public function getCount(int $isType = 0): int
    {
        $count = $this->count($isType);

        return $count ?? '0';
    }

    public function count(int $isType = 0)
    {
        return $this->query()
            ->when(!empty($isType), function ($query) use ($isType) {
                $query->where('is_type', $isType);
            })
            ->count();
    }

    public function sum(string $key, int $isType = 0)
    {
        return $this->query()
            ->when(!empty($isType), function ($query) use ($isType) {
                $query->where('is_type', $isType);
            })
            ->sum($key);
    }
}
