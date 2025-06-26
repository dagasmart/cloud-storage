<?php

namespace DagaSmart\CloudStorage\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Cache;

class CloudStorage extends Base
{
    protected $table = 'basic_cloud_storage';

    protected $primaryKey = 'id';

    public function config(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? json_decode($value, true) : [],
            set: fn ($value) => $value ? json_encode($value) : json_encode([])
        );
    }

    /**
     * 钩子
     */
    public static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (admin_user()) {
                $model->created_user = admin_user()->id;
            } else {
                $model->created_user = 0;
            }
        });

        static::created(function ($model) {
            $model->setCache($model);
        });

        static::updating(function ($model) {
            if (admin_user()) {
                $model->updated_user = admin_user()->id;
            } else {
                $model->updated_user = 0;
            }
            $model->setCache($model);
        });

        static::deleting(function ($model) {
            if (admin_user()) {
                $model->deleted_user = admin_user()->id;
            } else {
                $model->deleted_user = 0;
            }
            $model->clearCache();
        });
    }

    public function setCache($model): bool
    {
        if ($model->is_default == Base::ENABLE) {
            $data = [
                'id' => $model->id,
                'title' => $model->title,
                'driver' => $model->driver,
                'config' => $model->config,
                'file_size' => $model->file_size,
                'accept' => $model->accept,
                'is_default' => $model->is_default,
            ];

            return Cache::set(self::cache_cloud_storage_config_name(), $data);
        }

        return false;
    }

    public function getCache()
    {
        $data = Cache::get(self::cache_cloud_storage_config_name());
        if (!$data) {

            $row = $this->query()->where(['is_default' => self::ENABLE])->first();

            if (!$row) {
                $row = $this->query()
                    ->withoutGlobalScope('ActionScope')
                    ->where(['driver' => self::STORAGE_LOCAL])
                    ->where(['enabled' => self::ENABLE])
                    ->whereNull('module')
                    ->first();
            }

            if ($row) {

                $data = [
                    'id' => $row->id,
                    'title' => $row->title,
                    'driver' => $row->driver,
                    'config' => $row->config,
                    'file_size' => $row->file_size,
                    'accept' => $row->accept,
                    'is_default' => $row->is_default,
                ];
                Cache::set(self::cache_cloud_storage_config_name(), $data);

            }
        }

        return $data;
    }

    public function clearCache(): void
    {
        Cache::forget(self::cache_cloud_storage_config_name());
    }


}
