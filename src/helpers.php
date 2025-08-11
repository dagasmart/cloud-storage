<?php

use DagaSmart\BizAdmin\Admin;
use DagaSmart\CloudStorage\CloudStorageServiceProvider;

if (! function_exists('cloud_storage_trans')) {
    function cloud_storage_trans($key): array|string
    {
        $data = CloudStorageServiceProvider::trans('cloud-storage.'.$key);
        if ($key == 'driver_select' && !admin_current_module()) {
            array_unshift($data, ['label' => '本地存储', 'value' => 'local']);
        }
        return $data;
    }
}

if (! function_exists('storage_driver_default')) {
    function storage_driver_default($key): string|null
    {
        if ($storage_driver = cloud_storage_trans($key)) {
            return current(array_column($storage_driver,'value'));
        }
        return null;
    }
}

if (! function_exists('formatBytes')) {
    function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision).' '.$units[$pow];
    }
}

/**
 * 说明
 * 文件后缀判断，主要获取文件类型，根据类型存储不同目录
 * 判断依据根据env配置有关系
 * other属于其它类型
 *
 * @param  string  $fileName
 * @return string $format
 */
if (! function_exists('getAccept')) {
    function getAccept(string $fileName): string
    {
        $arr = explode('.', $fileName);
        $ext = end($arr);
        $ext = strtolower($ext);
        $document = env('CLOUD_STORAGE_DOCUMENT_EXTENSION', 'pdf,doc,docx,txt,xls,xlsx,ppt,pptx,zip,rar,7z,psd');
        $document = strtolower($document);
        $video = env('CLOUD_STORAGE_VIDEO_EXTENSION', 'mp4,avi,rmvb,rm,mov,flv,3gp,wmv,asf,asx,mpg,mpeg,mpe,ts,divx,webm,mkv,vob');
        $video = strtolower($video);
        $audio = env('CLOUD_STORAGE_AUDIO_EXTENSION', 'mp3,wav,wma,ogg,ape,flac,aac');
        $audio = strtolower($audio);
        $image = env('CLOUD_STORAGE_IMAGE_EXTENSION', 'jpg,jpeg,png,gif,bmp,webp,tiff,svg,ico');
        $image = strtolower($image);
        if (str_contains($document, $ext)) {
            $format = 'document';
        } elseif (str_contains($video, $ext)) {
            $format = 'video';
        } elseif (str_contains($audio, $ext)) {
            $format = 'audio';
        } elseif (str_contains($image, $ext)) {
            $format = 'image';
        } else {
            $format = 'other';
        }

        return $format;
    }
}

/**
 * 获取源文件名
 *
 * @param  string  $fileName
 * @return string
 */
if (! function_exists('getFileName')) {
    function getFileName(string $fileName): string
    {
        $arr = explode('/', $fileName);

        return end($arr);
    }
}

/**
 * 获取文件后缀及文件名
 */
if (! function_exists('getFilenameAndExtension')) {
    function getFilenameAndExtension($path): array
    {
        // Parse URL if it is a URL
        $parsed = @parse_url($path);
        if ($parsed !== false && isset($parsed['path'])) {
            $filePath = $parsed['path'];
        } else {
            $filePath = $path;
        }

        // Get the filename
        $fileName = basename($filePath);

        // Find the position of the last dot
        $lastDot = strrpos($fileName, '.');

        // Determine extension and filename
        if ($lastDot === false || $lastDot === 0 || $lastDot === strlen($fileName) - 1) {
            // No extension
            $extension = '';
            $filenameWithoutExtension = $fileName;
        } else {
            // Has extension
            $extension = substr($fileName, $lastDot + 1);
            $filenameWithoutExtension = substr($fileName, 0, $lastDot);
        }

        return [
            'filename' => $filenameWithoutExtension,
            'extension' => $extension,
        ];
    }
}

if (! function_exists('clear_array_trim')) {
    /**
     * 移除所有类型的空白字符（包括空格、制表符、换行符等）
     * @param $data
     * @return array|string
     */
    function clear_array_trim($data): array|string
    {
        //为数组时
        if (is_array($data) && count($data) > 0) {
            $json = json_encode($data, JSON_UNESCAPED_UNICODE);
            $json = preg_replace('/\s+/', '', $json);
            return json_decode($json, true);
        }
        //为字符串时
        return preg_replace('/\s+/', '', $data);
    }
}
