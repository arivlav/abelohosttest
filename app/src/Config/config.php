<?php

return [
    'debug' => getEnvData('APP_DEBUG', true),
    'app_env' => getEnvData('APP_ENV', 'local'),
    'db_host' => getEnvData('MYSQL_HOST', 'localhost'),
    'db_port' => getEnvData('MYSQL_PORT', '3306'),
    'db_user' => getEnvData('MYSQL_USER', 'root'),
    'db_password' => getEnvData('MYSQL_PASSWORD', ''),
    'db_name' => getEnvData('MYSQL_DATABASE', ''),
    'smarty' => [
        'template_dir' => __DIR__ . '/../../resources/templates',
        'compile_dir' => __DIR__ . '/../../resources/var/smarty/templates_c',
        'cache_dir' => __DIR__ . '/../../resources/var/smarty/cache'
    ],
    'image' => [
        'article_default' => '/images/articles/default.webp',
    ],
    'date' => [
        'commonFormat' => "M d, Y"
    ],
    'response_message' => [
        400 => '400 Bad Request',
        404 => '404 Not Found',
    ],
    'pagination' => [
        'perPage' => 5,
    ]
];
