<?php

namespace DagaSmart\CloudStorage\Http\Controllers;

use DagaSmart\BizAdmin\Controllers\AdminController;
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
     * 批量删除图片
     * Method DELETE
     * @param $ids
     * @return mixed
     */
    public function destroy($ids): mixed
    {
        //执行事务
        return DB::transaction(function () use ($ids) {
            //查询图片集合
            $images = $this->service->query()
                ->whereIn('id', explode(',', $ids))
                ->pluck('url')
                ->toArray();
            if ($images) {
                //执行删除图片操作
                Storage::disk('public')->delete($images);
            }
            // 执行模型的删除操作
            return parent::destroy($ids);
        });
    }
}
