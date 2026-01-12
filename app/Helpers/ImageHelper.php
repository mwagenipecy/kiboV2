<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageHelper
{
    /**
     * Optimize and resize image using GD library
     */
    public static function optimizeAndResize($image, $folder = 'images', $maxWidth = 1200, $quality = 85)
    {
        $extension = $image->getClientOriginalExtension();
        $filename = Str::uuid() . '.' . ($extension === 'png' ? 'png' : 'jpg');
        $path = $folder . '/' . $filename;
        
        try {
            // Get image resource
            if ($extension === 'png') {
                $sourceImage = imagecreatefrompng($image->getRealPath());
            } elseif ($extension === 'jpg' || $extension === 'jpeg') {
                $sourceImage = imagecreatefromjpeg($image->getRealPath());
            } elseif ($extension === 'gif') {
                $sourceImage = imagecreatefromgif($image->getRealPath());
            } elseif ($extension === 'webp') {
                $sourceImage = imagecreatefromwebp($image->getRealPath());
            } else {
                // Fallback - store as is
                return $image->store($folder, 'public');
            }
            
            if (!$sourceImage) {
                // Fallback if image creation fails
                return $image->store($folder, 'public');
            }
            
            // Get original dimensions
            $originalWidth = imagesx($sourceImage);
            $originalHeight = imagesy($sourceImage);
            
            // Calculate new dimensions
            if ($originalWidth <= $maxWidth) {
                $newWidth = $originalWidth;
                $newHeight = $originalHeight;
            } else {
                $newWidth = $maxWidth;
                $newHeight = intval(($originalHeight * $maxWidth) / $originalWidth);
            }
            
            // Create new image with new dimensions
            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            
            // Preserve transparency for PNG
            if ($extension === 'png') {
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                imagefill($newImage, 0, 0, $transparent);
            }
            
            // Resize image
            imagecopyresampled(
                $newImage, $sourceImage,
                0, 0, 0, 0,
                $newWidth, $newHeight,
                $originalWidth, $originalHeight
            );
            
            // Save optimized image
            $fullPath = storage_path('app/public/' . $path);
            $directory = dirname($fullPath);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
            
            if ($extension === 'png') {
                imagepng($newImage, $fullPath, 9);
            } else {
                imagejpeg($newImage, $fullPath, $quality);
            }
            
            // Free memory
            imagedestroy($sourceImage);
            imagedestroy($newImage);
            
            return $path;
            
        } catch (\Exception $e) {
            // Fallback to regular store if optimization fails
            return $image->store($folder, 'public');
        }
    }
}

