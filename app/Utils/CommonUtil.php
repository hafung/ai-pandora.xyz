<?php

namespace App\Utils;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CommonUtil {

    /**
     * @throws Exception
     */
    public static function generateUniqueStr(): string {
        return time() . sprintf("%03d", getmypid()) . sprintf('%07d', random_int(0, 9999999));
    }

    public static function getExtensionFromMimeType($mimeType): string {
        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
        ];

        return $extensions[$mimeType] ?? 'bin';
    }

    public static function uploadFileToQiniu(UploadedFile $file, $dir = '/tmp', $getFullUrl = true): string {

        $fileType = $file->getClientOriginalExtension();

        $disk = Storage::disk('qiniu');
        $fileName = $dir . uniqid() . '.' . $fileType;
        $uploaded = $disk->put($fileName, file_get_contents($file->getRealPath()));

        if (!$uploaded) {
            Log::error('downloadImage 失败: 上传失败');
            return '';
        }

        if ($getFullUrl) {
            return $disk->downloadUrl($fileName, 'https')->getUrl();
        }

        return $fileName;
    }

    public static function uploadBinaryToQiniu($binary, $fileName, $dir = '/tmp', $getFullUrl = true): string {

        $disk = Storage::disk('qiniu');

        $uploaded = $disk->put($dir . $fileName, $binary);

        if (!$uploaded) {
            Log::error('uploadBinaryToQiniu 失败: 上传失败');
            return '';
        }

        if ($getFullUrl) {
            return $disk->downloadUrl($dir . $fileName, 'https')->getUrl();
        }

        return $dir . $fileName;
    }
}
