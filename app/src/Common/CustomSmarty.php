<?php

namespace App\Common;

use App\Common\Config;
use Smarty\Smarty;

class CustomSmarty extends Smarty
{
    public function __construct()
    {
        parent::__construct();
        $smartySettings = Config::get('smarty');
        foreach ($smartySettings as $dir) {
            if (!\is_dir($dir)) {
                @\mkdir($dir, 0775, true);
            }
        }

        $this->setTemplateDir($smartySettings['template_dir']);
        $this->setCompileDir($smartySettings['compile_dir']);
        $this->setCacheDir($smartySettings['cache_dir']);
//        $this->caching = false;
//        $this->force_compile = true;
    }
}