<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ArticlesController;

return [
    '\/' => [CategoriesController::class, 'index'],
    '\/categories\/\d+' => [CategoriesController::class, 'show'],
    '\/articles\/\d+' => [ArticlesController::class, 'show']
];