<?php

use App\Enums\Response;

if (!function_exists('getEnvData')) {
    function getEnvData(mixed $value, mixed $default): mixed
    {
        $current = getenv($value);
        if ($current === false) {
            return $default;
        }
        return $current;
    }
}

if (!function_exists('getResponseCodeAndMessage')) {
    function getResponseCodeAndMessage(int $code, array $errors = []): mixed
    {
        http_response_code($code);
        $message = \App\Common\Config::get('response_message')[$code];
        if ($errors) {
            $message .= '<br>' . implode('<br>', $errors);
        }
        return $message;
    }
}
