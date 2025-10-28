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

    protected function localDriver(): array
    {
        return [
            'id' => 1,
            'title' => '本地存储',
            'driver' => 'local',
            'config' => ['root' => 'uploads', 'domain' => env('APP_URL')],
            'file_size' => 10,
            'enabled' => 1,
            'is_default' => 1,
        ];
    }

    public function cache_cloud_storage_config_name(): string
    {
        $data = self::CACHE_CLOUD_STORAGE_CONFIG_NAME;

        if ($module = admin_current_module()) {
            $data .= '_' . $module; //追加模块
        }

        if ($merId = admin_mer_id()) {
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
