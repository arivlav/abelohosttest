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
            if ($article) {
                return [
                    'title' => $article['name'],
                    'article' => $article,
                ];
            }
        }
        return [];
    }
}