<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Imagick;
use Throwable;

class ImageCompressionService
{
    /** When resizing with GD, pre-scale if the longest edge exceeds this (reduces time/memory on huge uploads). */
    private const GD_PRESCALE_THRESHOLD_PX = 4000;

    /** PNG compression 0–9. Level 9 is very slow on the server; 6 is a good balance. */
    private const PNG_COMPRESSION_LEVEL = 6;

    /** Headroom for multiple images in one HTTP request (Livewire save, etc.). */
    private const EXECUTION_TIME_BUFFER_SECONDS = 300;

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
        $this->relaxRuntimeLimits();

        $extension = strtolower($image->getClientOriginalExtension() ?: '');

        $filename = Str::uuid().'.'.($extension === 'png' ? 'png' : 'jpg');
        $path = $folder.'/'.$filename;

        $realPath = $image->getRealPath();
        if ($realPath === false || ! is_readable($realPath)) {
            return $image->store($folder, 'public');
        }

        if (extension_loaded('imagick')) {
            try {
                return $this->storeCompressedWithImagick($realPath, $path, $extension, $maxWidth, $quality);
            } catch (Throwable) {
                // Fall through to GD
            }
        }

        try {
            return $this->storeCompressedWithGd($image, $path, $extension, $maxWidth, $quality);
        } catch (Throwable) {
            return $image->store($folder, 'public');
        }
    }

    private function relaxRuntimeLimits(): void
    {
        if (! function_exists('set_time_limit')) {
            return;
        }

        $current = (int) ini_get('max_execution_time');
        if ($current === 0) {
            return;
        }

        @set_time_limit(max($current, self::EXECUTION_TIME_BUFFER_SECONDS));

        $memory = (string) ini_get('memory_limit');
        $bytes = $this->parseIniSizeToBytes($memory);
        if ($bytes > 0 && $bytes < 512 * 1024 * 1024) {
            @ini_set('memory_limit', '512M');
        }
    }

    private function parseIniSizeToBytes(string $value): int
    {
        $value = trim($value);
        if ($value === '' || $value === '-1') {
            return PHP_INT_MAX;
        }

        $unit = strtolower(substr($value, -1));
        $num = (float) $value;
        if (in_array($unit, ['g', 'm', 'k'], true)) {
            $num = (float) substr($value, 0, -1);
        } else {
            $unit = '';
        }

        return match ($unit) {
            'g' => (int) ($num * 1024 * 1024 * 1024),
            'm' => (int) ($num * 1024 * 1024),
            'k' => (int) ($num * 1024),
            default => (int) $num,
        };
    }

    /**
     * @throws Throwable
     */
    private function storeCompressedWithImagick(
        string $realPath,
        string $path,
        string $extension,
        int $maxWidth,
        int $quality
    ): string {
        $imagick = new Imagick($realPath);
        try {
            if ($imagick->getNumberImages() > 1) {
                $flattened = $imagick->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
                $imagick->clear();
                $imagick->destroy();
                $imagick = $flattened;
            }

            $imagick->autoOrient();
            $imagick->stripImage();

            $w = $imagick->getImageWidth();
            $h = $imagick->getImageHeight();

            if ($w > 0 && $h > 0 && $w > $maxWidth) {
                $newH = (int) (($h * $maxWidth) / $w);
                $imagick->resizeImage($maxWidth, $newH, Imagick::FILTER_LANCZOS, 1);
            }

            $fullPath = storage_path('app/public/'.$path);
            $directory = dirname($fullPath);
            if (! is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            if ($extension === 'png') {
                $imagick->setImageFormat('png');
                $imagick->setImageCompression(Imagick::COMPRESSION_ZIP);
                $imagick->setOption('png:compression-level', (string) self::PNG_COMPRESSION_LEVEL);
            } else {
                $imagick->setImageFormat('jpeg');
                $imagick->setImageCompressionQuality($quality);
            }

            $imagick->writeImage($fullPath);

            return $path;
        } finally {
            $imagick->clear();
            $imagick->destroy();
        }
    }

    private function storeCompressedWithGd(
        UploadedFile $image,
        string $path,
        string $extension,
        int $maxWidth,
        int $quality
    ): string {
        $sourceImage = match ($extension) {
            'png' => @imagecreatefrompng($image->getRealPath()),
            'jpg', 'jpeg' => @imagecreatefromjpeg($image->getRealPath()),
            'gif' => @imagecreatefromgif($image->getRealPath()),
            'webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($image->getRealPath()) : false,
            default => false,
        };

        if (! $sourceImage) {
            return $image->store(dirname($path), 'public');
        }

        $originalWidth = imagesx($sourceImage);
        $originalHeight = imagesy($sourceImage);

        if ($originalWidth <= 0 || $originalHeight <= 0) {
            imagedestroy($sourceImage);

            return $image->store(dirname($path), 'public');
        }

        $sourceImage = $this->gdPrescaleLargeSource(
            $sourceImage,
            $originalWidth,
            $originalHeight,
            $extension,
            $maxWidth
        );
        $originalWidth = imagesx($sourceImage);
        $originalHeight = imagesy($sourceImage);

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
            imagepng($newImage, $fullPath, self::PNG_COMPRESSION_LEVEL);
        } else {
            imagejpeg($newImage, $fullPath, $quality);
        }

        imagedestroy($sourceImage);
        imagedestroy($newImage);

        return $path;
    }

    /**
     * One intermediate downscale when the source is huge, so the final resample is cheaper.
     *
     * @param  \GdImage|resource  $sourceImage
     * @return \GdImage|resource
     */
    private function gdPrescaleLargeSource($sourceImage, int $originalWidth, int $originalHeight, string $extension, int $maxWidth)
    {
        $longest = max($originalWidth, $originalHeight);
        if ($longest <= self::GD_PRESCALE_THRESHOLD_PX) {
            return $sourceImage;
        }

        $ratio = (self::GD_PRESCALE_THRESHOLD_PX / $longest) * 0.95;
        $iw = max(1, (int) ($originalWidth * $ratio));
        $ih = max(1, (int) ($originalHeight * $ratio));

        $intermediate = imagecreatetruecolor($iw, $ih);
        if ($extension === 'png') {
            imagealphablending($intermediate, false);
            imagesavealpha($intermediate, true);
            $transparent = imagecolorallocatealpha($intermediate, 255, 255, 255, 127);
            imagefill($intermediate, 0, 0, $transparent);
        }

        imagecopyresampled(
            $intermediate,
            $sourceImage,
            0,
            0,
            0,
            0,
            $iw,
            $ih,
            $originalWidth,
            $originalHeight
        );
        imagedestroy($sourceImage);

        return $intermediate;
    }
}
