<?php

namespace DagaSmart\CloudStorage\Models;

use DagaSmart\BizAdmin\Admin;
use DagaSmart\CloudStorage\Services\CloudUploadService;
use Exception;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dagasmart\BizAdmin\Models\BaseModel as Model;

class Base extends Model
{
    //use SoftDeletes;

    const int ENABLE = 1;

    const int FORBIDDEN = 0;

    const string STORAGE_LOCAL = 'local';

    const string CACHE_CLOUD_STORAGE_CONFIG_NAME = 'cache_cloud_storage';

    public function cache_cloud_storage_config_name(): string
    {
        $data = self::CACHE_CLOUD_STORAGE_CONFIG_NAME;

        if ($module = Admin::currentModule(true)) {
            $data .= '_' . $module; //追加模块
        }

        if (!is_null($merId = Admin::MerId())) {
            $data .= '_' . $merId; //追加商户
        }

        return $data;
    }
    public function img(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? $this->getCloudStoragePath($value) : '',
            set: fn ($value) => $value
        );
    }

    /**
     * 获取加密链接
     *
     * @throws Exception
     */
    public function getCloudStoragePath($value, $id = null): string
    {
        $cloudUploadService = new CloudUploadService;
        return $cloudUploadService->signUrl($value, $id);
    }
}
