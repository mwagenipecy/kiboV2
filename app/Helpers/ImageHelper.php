<?php

namespace App\Helpers;

use App\Services\ImageCompressionService;
use Illuminate\Http\UploadedFile;

class ImageHelper
{
    public static function optimizeAndResize(UploadedFile $image, string $folder = 'images', int $maxWidth = 1200, int $quality = 85): string
    {
        return app(ImageCompressionService::class)->storeCompressed($image, $folder, $maxWidth, $quality);
    }
}
