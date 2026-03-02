<?php

namespace App\Common;

class App
{
    public static function init(): void {
        self::loadSettings();
        switch (Config::get('app_env')) {
            case 'local':
                error_reporting(E_ALL);
                break;
            case 'production':
                error_reporting(0);
        }

        if (Config::get('debug')) {
            ini_set('display_errors', 1);
        }
        Route::dispatch(new Request()->getUrl());
    }

    private static function loadSettings(): void {
        Config::load();
        Route::load();
    }
}