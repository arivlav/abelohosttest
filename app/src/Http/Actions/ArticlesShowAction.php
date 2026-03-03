<?php

namespace App\Http\Actions;

use App\Services\ArticleService;

readonly class ArticlesShowAction implements IAction
{
    public function __construct(
        private ArticleService $articleService
    ) {}
    public function execute(?int $id = null): array {
        if ($id !== null) {
            $this->articleService->increaseViewsById($id);
            $article = $this->articleService->findById($id);
            $similarArticles = $this->articleService->findRandTreeForCategories([$article['category_id']], [$article['id']]);
            if ($article) {
                return [
                    'title' => $article['name'],
                    'article' => $article,
                    'similarArticles' => $similarArticles[array_key_first($similarArticles)]
                ];
            }
        }
        return [];
    }
}