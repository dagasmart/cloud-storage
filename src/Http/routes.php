<?php

use DagaSmart\CloudStorage\Http\Controllers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::get('cloud_storage', [Controllers\CloudStorageController::class, 'index']);

Route::group([
    'prefix' => 'cloud_storage',
    'middleware' => [],
], function (Router $router) {
    // 获取列表
    Route::get('resource/get_list', [Controllers\CloudResourceController::class, 'get_list']);

    // 下载
    Route::get('resource/download/{id}', [Controllers\CloudResourceController::class, 'download']);
    // 简单上传
    Route::post('upload/receiver/{id}', [Controllers\CloudUploadController::class, 'receiver']);
    // 切片上传
    // 开始上传文件的准备
    Route::post('upload/startChunk/{id}', [Controllers\CloudUploadController::class, 'startChunk']);
    // 分段上传文件
    Route::post('upload/chunk/{id}', [Controllers\CloudUploadController::class, 'chunk']);
    // 完成分片上传
    Route::post('upload/finishChunk/{id}', [Controllers\CloudUploadController::class, 'finishChunk']);

    // 存储设置
    $router->resource('storage', Controllers\CloudStorageController::class);
    // 资源管理
    $router->resource('resource', Controllers\CloudResourceController::class);
});

//需登录与鉴权
//Route::group([
//    'middleware' => config('admin.route.middleware'),
//], function (Router $router) {
//
//    // 获取单纯列表数据
//    $router->get('cloud_storage/resource/getList', [CloudResourceController::class, 'getList']);
//    // 下载
//    $router->get('cloud_storage/resource/download/{id}', [CloudResourceController::class, 'download']);
//    // 简单上传
//    $router->post('cloud_storage/upload/receiver/{id}', [CloudUploadController::class, 'receiver']);
//    // 切片上传
//    // 开始上传文件的准备
//    $router->post('cloud_storage/upload/startChunk/{id}', [CloudUploadController::class, 'startChunk']);
//    // 分段上传文件
//    $router->post('cloud_storage/upload/chunk/{id}', [CloudUploadController::class, 'chunk']);
//    // 完成分片上传
//    $router->post('cloud_storage/upload/finishChunk/{id}', [CloudUploadController::class, 'finishChunk']);
//
//    // 存储设置
//    $router->resource('cloud_storage/storage', CloudStorageController::class);
//    // 资源管理
//    $router->resource('cloud_storage/resource', CloudResourceController::class);
//});



//// 获取单纯列表数据
//Route::get('cloud_storage/resource/getList', [CloudResourceController::class, 'getList']);
//// 下载
//Route::get('cloud_storage/resource/download/{id}', [CloudResourceController::class, 'download']);
//// 简单上传
//Route::post('cloud_storage/upload/receiver/{id}', [CloudUploadController::class, 'receiver']);
//// 切片上传
//// 开始上传文件的准备
//Route::post('cloud_storage/upload/startChunk/{id}', [CloudUploadController::class, 'startChunk']);
//// 分段上传文件
//Route::post('cloud_storage/upload/chunk/{id}', [CloudUploadController::class, 'chunk']);
//// 完成分片上传
//Route::post('cloud_storage/upload/finishChunk/{id}', [CloudUploadController::class, 'finishChunk']);

//// 存储设置
//Route::resource('cloud_storage/storage', CloudStorageController::class);
//// 资源管理
//Route::resource('cloud_storage/resource', CloudResourceController::class);
