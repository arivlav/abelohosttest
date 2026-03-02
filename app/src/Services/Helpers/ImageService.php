<?php

namespace App\Services\Helpers;

use App\Common\Config;

class ImageService
{
    public static function getArticleImage(string $filename): string {
        if (file_exists($filename)) {
            return $filename;
        }

        return Config::get('image')['article_default'];
    }
}