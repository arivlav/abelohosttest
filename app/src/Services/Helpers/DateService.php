<?php

namespace App\Services\Helpers;

use App\Common\Config;

class DateService
{
    public static function getCommonFormatDate(string $date): string {
        $commonFormat = Config::get('date')['commonFormat'];
        return date($commonFormat, strtotime($date));
    }
}