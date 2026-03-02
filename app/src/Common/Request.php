<?php

namespace App\Common;

class Request
{
    public function getUrl(): string
    {
        $url = explode('?', $_SERVER['REQUEST_URI'])[0];
        if ($url === '/index.php') {
            return '/';
        }
        return $url;
    }

    public function getArgFromUrl(): ?int
    {
        $url = $this->getUrl();
        if (preg_match('/\d+/', $url, $match)) {
            return $match[0];
        }
        return null;
    }

    public function getParams(): array
    {
        return $_GET;
    }
}