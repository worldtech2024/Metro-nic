<?php

namespace App\Trait;

use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait UploadFileTrait
{
    public static function store(UploadedFile $file, string $publicStoragePath): string
    {
        $path = $file->store($publicStoragePath, 'public');
        return $path;
    }

    public static function delete(string $path)
    {
        if (Storage::exists('public/' . $path)) {
            Storage::delete('public/' . $path);
            return true;
        }
        return false;
    }

    public function handleImageUpload(Request $request, string $fieldName, ?string $oldImage = null, string $path = 'uploads/images')
    {
        if ($request->hasFile($fieldName) && $request->file($fieldName)->isValid()) {

           
            if (!empty($oldImage)) {
                $oldImagePath = str_replace('storage/', '', $oldImage);

                if (Storage::disk('public')->exists($oldImagePath)) {
                    Storage::disk('public')->delete($oldImagePath);
                }
            }

         
            $uploadedFilePath = $request->file($fieldName)->store($path, 'public');

          
            return 'storage/' . $uploadedFilePath;
        }

        return $oldImage; 
    }
}