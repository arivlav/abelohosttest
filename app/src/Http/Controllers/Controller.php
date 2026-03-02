<?php

namespace App\Http\Controllers;

use App\Common\CustomSmarty;

class Controller
{
    protected function view(string $template, array $data = []): string
    {
        try {
            $smarty = new CustomSmarty();

            if (!str_ends_with($template, '.tpl')) {
                $template .= '.tpl';
            }

            foreach ($data as $key => $value) {
                $smarty->assign((string)$key, $value);
            }

            return $smarty->fetch($template);
        } catch (\Throwable $e) {
            throw new \Exception("Ошибка рендера шаблона Smarty: " . $e->getMessage());
        }
    }
}

