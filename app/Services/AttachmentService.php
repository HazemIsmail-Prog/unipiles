<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class AttachmentService
{
    public static function saveToDisk($file,$parentId,$subFolderName)
    {
        $filename = $parentId . '_' . rand(100000, 999999) . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('attachments/' . $subFolderName, $filename, 's3');
        return $path;
    }

    public static function deleteFromDisk($path)
    {
        if (Storage::disk('s3')->exists($path)) {
            Storage::disk('s3')->delete($path);
        }
    }
}
