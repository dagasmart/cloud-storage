<?php

namespace DagaSmart\CloudStorage;

use DagaSmart\BizAdmin\Extend\Extension;
use Dagasmart\BizAdmin\Extend\ServiceProvider;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

class CloudStorageServiceProvider extends ServiceProvider
{
    protected $menu = [
        [
            'title' => '云存储管理',
            'url' => '/cloud_storage',
            'url_type' => '1',
            'icon' => 'tdesign:object-storage',
        ],
        [
            'parent' => '云存储管理', // 此处父级菜单根据 title 查找
            'title' => '资源管理',
            'url' => '/cloud_storage/resource',
            'url_type' => '1',
            'icon' => 'ant-design:file-protect-outlined',
        ],
        [
            'parent' => '云存储管理', // 此处父级菜单根据 title 查找
            'title' => '存储设置',
            'url' => '/cloud_storage/storage',
            'url_type' => '1',
            'icon' => 'carbon:settings-check',
        ],
    ];

    public function settingForm(): null
    {
        return null;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function boot(): void
    {
        if (Extension::tableExists()) {
            $this->runMigrations();
            $this->autoRegister();
            $this->init();
        }
        parent::boot();
    }
}
