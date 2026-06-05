<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HandlesImageUploads
{
    /**
     * Store an uploaded image on the public disk and return its relative path.
     */
    protected function storeImage(UploadedFile $file, string $folder = 'uploads'): string
    {
        $name = Str::random(20) . '.' . $file->getClientOriginalExtension();

        return $file->storeAs($folder, $name, 'public');
    }

    /**
     * Delete an image from the public disk if it exists.
     */
    protected function deleteImage(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Replace an existing image: delete old, store new. Returns new path or old if no new file.
     */
    protected function replaceImage($file, ?string $oldPath, string $folder = 'uploads'): ?string
    {
        if ($file instanceof UploadedFile) {
            $this->deleteImage($oldPath);

            return $this->storeImage($file, $folder);
        }

        return $oldPath;
    }
}
