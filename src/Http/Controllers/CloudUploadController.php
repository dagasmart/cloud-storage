<?php

namespace DagaSmart\CloudStorage\Http\Controllers;

use DagaSmart\CloudStorage\Services\CloudUploadService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class CloudUploadController extends BaseController
{
    protected CloudUploadService $uploadService;

    public function __construct()
    {
        parent::__construct();
        $this->uploadService = new CloudUploadService;
    }

    public function receiver($id): JsonResponse|JsonResource
    {
        try {
            $data = $this->uploadService->receiver($id);

            return $this->response()->success($data);
        } catch (Exception $e) {
            return $this->response()->fail($e->getMessage());
        }
    }

    /**
     * 开始上传文件的准备
     */
    public function startChunk($id): JsonResponse|JsonResource
    {
        try {
            $data = $this->uploadService->startChunk($id);

            return $this->response()->success($data);
        } catch (Exception $e) {
            //抛出错误信息
            return $this->response()->fail($e->getMessage());
        }
    }

    /**
     * 分段上传文件
     */
    public function chunk($id): JsonResponse|JsonResource
    {
        try {
            $data = $this->uploadService->chunk($id);

            return $this->response()->success($data);
        } catch (Exception $e) {
            return $this->response()->fail($e->getMessage());
        }
    }

    /**
     * 完成分片上传
     */
    public function finishChunk($id): JsonResponse|JsonResource
    {
        try {
            //接取视频
            $data = $this->uploadService->finishChunk($id);

            return $this->response()->success($data);
        } catch (Exception $e) {
            return $this->response()->fail($e->getMessage());
        }
    }
}
