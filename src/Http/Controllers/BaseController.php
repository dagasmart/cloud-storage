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
                $resourceModel = new $getMorphClass;
                $column = 'id';
                $flag = false;
            } else { //否则，加载资源模型类
                $resourceModel = new CloudResource;
                $column = 'storage_id';
                $flag = true;
            }

            if ($flag) {
                // 执行模型的删除操作
                parent::destroy($ids);

                //查询图片集合
                $pluck = $resourceModel->query()
                    ->whereIn($column, explode(',', $ids))
                    ->pluck('url','id')
                    ->toArray();
                if ($pluck && $storageIds = array_keys($pluck)) {

                    //删除资源表记录
                    $resourceModel::destroy($storageIds);

                    if ($images = array_values($pluck)) {
                        //执行删除图片操作
                        Storage::disk('public')->delete($images);
                    } else {
                        return true;
                    }
                } else {
                    return true;
                }
            }
            return false;
        });
    }
}
