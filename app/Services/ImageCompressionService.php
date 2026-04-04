<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageCompressionService
{
    /**
     * Compress images; store non-image files (e.g. PDF) unchanged.
     */
    public function storeCompressedIfImage(
        UploadedFile $file,
        string $folder,
        int $maxWidth = 1200,
        int $quality = 85
    ): string {
        $mime = (string) $file->getMimeType();

        if ($mime !== '' && str_starts_with($mime, 'image/')) {
            return $this->storeCompressed($file, $folder, $maxWidth, $quality);
        }

        return $file->store($folder, 'public');
    }

    /**
     * Resize (max width) and compress an uploaded image, then store it on the public disk.
     * Returns the path relative to storage/app/public (same shape as UploadedFile::store).
     */
    public function storeCompressed(
        UploadedFile $image,
        string $folder = 'images',
        int $maxWidth = 1200,
        int $quality = 85
    ): string {
        $extension = strtolower($image->getClientOriginalExtension() ?: '');

        $filename = Str::uuid().'.'.($extension === 'png' ? 'png' : 'jpg');
        $path = $folder.'/'.$filename;

        try {
            $sourceImage = match ($extension) {
                'png' => @imagecreatefrompng($image->getRealPath()),
                'jpg', 'jpeg' => @imagecreatefromjpeg($image->getRealPath()),
                'gif' => @imagecreatefromgif($image->getRealPath()),
                'webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($image->getRealPath()) : false,
                default => false,
            };

            if (! $sourceImage) {
                return $image->store($folder, 'public');
            }

            $originalWidth = imagesx($sourceImage);
            $originalHeight = imagesy($sourceImage);

            if ($originalWidth <= 0 || $originalHeight <= 0) {
                imagedestroy($sourceImage);

                return $image->store($folder, 'public');
            }

            if ($originalWidth <= $maxWidth) {
                $newWidth = $originalWidth;
                $newHeight = $originalHeight;
            } else {
                $newWidth = $maxWidth;
                $newHeight = (int) (($originalHeight * $maxWidth) / $originalWidth);
            }

            $newImage = imagecreatetruecolor($newWidth, $newHeight);

            if ($extension === 'png') {
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                imagefill($newImage, 0, 0, $transparent);
            }

            imagecopyresampled(
                $newImage,
                $sourceImage,
                0,
                0,
                0,
                0,
                $newWidth,
                $newHeight,
                $originalWidth,
                $originalHeight
            );

            $fullPath = storage_path('app/public/'.$path);
            $directory = dirname($fullPath);
            if (! is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            if ($extension === 'png') {
                imagepng($newImage, $fullPath, 9);
            } else {
                imagejpeg($newImage, $fullPath, $quality);
            }

            imagedestroy($sourceImage);
            imagedestroy($newImage);

            return $path;
        } catch (\Throwable) {
            return $image->store($folder, 'public');
        }
    }
}
