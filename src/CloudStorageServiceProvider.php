<?php

namespace DagaSmart\CloudStorage;

use DagaSmart\BizAdmin\Extend\Extension;
use Dagasmart\BizAdmin\Extend\ServiceProvider;
use DagaSmart\CloudStorage\Services\CloudUploadService;

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

    public function boot(): void
    {
        if (Extension::tableExists()) {
            parent::boot();
        }
    }

    public function register(): void
    {
        parent::register();

        /**加载路由**/
        parent::registerRoutes(__DIR__.'/Http/routes.php');
        /**加载语言包**/
        if ($lang = parent::getLangPath()) {
            $this->loadTranslationsFrom($lang, $this->getCode());
        }

        $this->app->singleton('admin.cloud.upload', CloudUploadService::class);
    }

}
