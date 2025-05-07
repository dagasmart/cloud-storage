# BizAdmin Extension

## 云存储管理
支持 本地，腾讯云、阿里云等OSS云存储功能，支持一键迁移，资源展示等功能。

## 安装

#### zip 下载地址

#### composer

```bash
composer require dagasmart/cloud-storage
```

## 使用说明

1. 安装扩展
2. 在扩展管理中启用扩展

## 使用方法

### 配置

需要配置存储方式才能调用

### 调用

选项组件，在form表单中调用
```php
use DagaSmart\CloudStorage\Traits\UploadPickerTrait;
class CloudResourceController extends BaseController
{
    use UploadPickerTrait;
    
    // 调用方法
    $this->uploadPicker('icon', __('admin.admin_menu.icon'));
}
```

上传文件
```php
use DagaSmart\CloudStorage\Traits\UploadTrait;

//获取文件下载链接
$this->cloudGetUrl($resource_id, $openDomain = '');

//简单上传
$this->cloudSimpleUpload($file_path, $storage_id, $fileName = '');

//分片上传
$this->cloudChunkUpload($file_path, $storage_id, $fileName = '', $chunk_size = 50 * 1024 * 1024, $min_size = 5 * 1024 * 1024 * 1024);
```
### 注意事项


