<?php

namespace DagaSmart\CloudStorage\Models;

use DagaSmart\CloudStorage\Services\CloudResourceService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CloudResource extends Base
{
    use HasUlids;

    protected $table = 'basic_cloud_resource';

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
            if (admin_current_module()) {
                $model->module = admin_current_module();
            }
            if (!is_null(admin_mer_id())) {
                $model->mer_id = admin_mer_id();
            }
        });
        static::deleting(function ($model) {
            if (admin_user()) {
                $model->deleted_user = admin_user()->id;
            } else {
                $model->deleted_user = 0;
            }
        });
    }

    //    public function url(): Attribute
    //    {
    //        return Attribute::make(
    //            get: fn ($value) => $value ? [
    //                'path' => $value,
    //                'value' => $this->getCloudStoragePath($value),
    //            ] : [],
    //            set: fn ($value) => $value
    //        );
    //    }

    public function size(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $value ? formatBytes($value) : 0,
            set: fn ($value) => $value,
        );
    }

    public function isType(): Attribute
    {
        $cloudResourceService = new CloudResourceService;

        return new Attribute(
            get: fn ($value) => $value ? $cloudResourceService::fileType[$value] : 0,
            set: fn ($value) => $value,
        );
    }

    public function storage(): HasOne
    {
        return $this->hasOne(CloudStorage::class, 'id', 'storage_id');
    }
}
