<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

trait StorageImageTrait
{
    public function storageTraitUpload($request, $fieldName, $foderName)
    {
        if ($request->hasFile($fieldName)) {
            $file = $request->$fieldName;
            // File gốc
            $filenameOrigin = $file->getClientOriginalName();
            // File lưu trên database
            $fileNameHash = Str::random(20) . '.' . $file->getClientOriginalExtension();
            // auth()->id(): là trỏ đến id upload ảnh, vd: thư mục một là của user1 á
            $filePath = $request->file($fieldName)->storeAs('public/' . $foderName . '/' . auth()->id(), $fileNameHash);
            $dataUploadTrait = [
                'file_name' => $filenameOrigin,
                'file_path' => Storage::url($filePath), // Thay đường dẫn từ storage ->public/storage trên thôi
            ];
            return $dataUploadTrait;
        }
        return null;
    }

    public function storageTraitUploadMutiple($file, $foderName)
    {
        // File gốc
        $filenameOrigin = $file->getClientOriginalName();
        // File lưu trên database
        $fileNameHash = Str::random(20) . '.' . $file->getClientOriginalExtension();
        // auth()->id(): là trỏ đến id upload ảnh, vd: thư mục một là của user1 á
        $filePath = $file->storeAs('public/' . $foderName . '/' . auth()->id(), $fileNameHash);
        $dataUploadTrait = [
            'file_name' => $filenameOrigin,
            'file_path' => Storage::url($filePath),
        ];
        return $dataUploadTrait;
    }
}
