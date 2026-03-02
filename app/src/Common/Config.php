<?php

namespace App\Common;

class Config
{
    private static array $settings;

    public static function load(): mixed {
        return self::$settings = require __DIR__ . "/../Config/config.php";
    }

    public static function get(string $key): mixed {
        $settings = self::$settings;
        return $settings[$key] ?? null;
    }
}