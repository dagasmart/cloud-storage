<?php

namespace DagaSmart\CloudStorage\Http\Controllers;

use DagaSmart\BizAdmin\Controllers\AdminController;
use DagaSmart\CloudStorage\Models\CloudResource;
use DagaSmart\CloudStorage\Traits;
use Dagasmart\BizAdmin\Renderers\Form;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BaseController extends AdminController
{
    use Traits\CloudStorageQueryPathTrait;

    public function detail(): Form
    {
        return $this->baseDetail()->body([]);
    }

    /**
     * 批量删除
     * Method DELETE
     * @param $ids
     * @return mixed
     */
    public function destroy($ids): mixed
    {
        //执行事务
        return DB::transaction(function () use ($ids) {

            $getMorphClass = $this->service->getModel()->getMorphClass();

            //判断是否资源模型类
            if (str_contains($getMorphClass, 'CloudResource')) {
                $column = 'id';
                $flag = false;
            } else { //否则，加载存储模型类
                $column = 'storage_id';
                $flag = true;
            }

            //资源实例化
            $resourceModel = new CloudResource;
            //查询图片集合
            $pluck = $resourceModel->query()
                ->whereIn($column, explode(',', $ids))
                ->pluck('url','id');

            //true表示当前为存储模型调用
            if ($flag) {
                // 执行模型的删除操作
                if (parent::destroy($ids)) {
                    (new $getMorphClass)->clearCache();
                    $this->service->getModel();
                    if (!$pluck->isEmpty()) {
                        $pluck = $pluck->toArray();
                        //删除资源表记录
                        $resourceModel::destroy(array_keys($pluck));
                        //执行删除图片操作
                        Storage::disk('public')->delete(array_values($pluck));
                    }
                    return $this->autoResponse(true, admin_trans('admin.delete'));
                }
            } else {
                if (parent::destroy($ids)) {
                    if (!$pluck->isEmpty()) {
                        $pluck = $pluck->toArray();
                        //执行删除图片操作
                        Storage::disk('public')->delete(array_values($pluck));
                    }
                    return $this->autoResponse(true, admin_trans('admin.delete'));
                }
            }
            return $this->autoResponse(false, admin_trans('admin.delete'));
        });
    }
}
