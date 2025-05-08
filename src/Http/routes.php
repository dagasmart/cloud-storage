<?php

use DagaSmart\CloudStorage\Http\Controllers\CloudResourceController;
use DagaSmart\CloudStorage\Http\Controllers\CloudStorageController;
use DagaSmart\CloudStorage\Http\Controllers\CloudUploadController;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::get('cloud_storage', [CloudStorageController::class, 'index']);

//需登录与鉴权
Route::group([
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {
    // 存储设置
    $router->resource('cloud_storage/storage', CloudStorageController::class);
    // 资源管理
    $router->resource('cloud_storage/resource', CloudResourceController::class);

    // 获取单纯列表数据
    $router->get('get/cloud_storage/resource/getList', [CloudResourceController::class, 'getList']);
    // 下载
    $router->get('cloud_storage/resource/download/{id}', [CloudResourceController::class, 'download']);
    // 简单上传
    $router->post('cloud_storage/upload/receiver/{id}', [CloudUploadController::class, 'receiver']);
    // 切片上传
    // 开始上传文件的准备
    $router->post('cloud_storage/upload/startChunk/{id}', [CloudUploadController::class, 'startChunk']);
    // 分段上传文件
    $router->post('cloud_storage/upload/chunk/{id}', [CloudUploadController::class, 'chunk']);
    // 完成分片上传
    $router->post('cloud_storage/upload/finishChunk/{id}', [CloudUploadController::class, 'finishChunk']);
});

