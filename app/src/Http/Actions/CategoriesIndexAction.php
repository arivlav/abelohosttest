<?php

namespace App\Http\Actions;

use App\Repositories\PDOCategoryRepository;
use App\Services\ArticleService;
use App\Services\CategoryService;

readonly class CategoriesIndexAction implements IAction
{
    public function __construct(
        private PDOCategoryRepository $categoryRepository,
        private ArticleService $articleService

    ) {}
    public function execute(?int $id = null): array {
        return [
            'title' => 'Main',
            'categories' => $this->categoryRepository->all(),
            'articles' => $this->articleService->findRandTreeForCategories(),
        ];
    }
}