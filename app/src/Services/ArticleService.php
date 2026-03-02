<?php

namespace App\Services;

use App\Repositories\PDOArticleRepository;
use App\Services\Helpers\DateService;
use App\Services\Helpers\ImageService;

readonly class ArticleService
{
    public function __construct(
        private PDOArticleRepository $articleRepository
    )
    {
    }

    public function findById(int $id): array
    {
        $article = $this->articleRepository->findById($id);
        if ($article) {
            return [
                'id' => (int)$article['id'],
                'category_id' => (int)$article['category_id'],
                'image' => ImageService::getArticleImage($article['image'] ?? ''),
                'name' => $article['name'],
                'description' => $article['description'] ?? '',
                'content' => $article['content'],
                'views' => (int)($article['views'] ?? 0),
                'published_at' => DateService::getCommonFormatDate($article['published_at']),
            ];
        }
        return $article;
    }

    public function findRandTreeForAllCategories(): array
    {
        $formattedArticles = [];
        $articles = $this->articleRepository->findRandTreeForAllCategories();
        foreach ($articles as $article) {
            $categoryId = (int)$article['category_id'];
            $article[$categoryId] ??= [];

            if ($article['article_id'] === null) {
                continue;
            }

            $formattedArticles[$categoryId][] = [
                'id' => (int)$article['article_id'],
                'image' => ImageService::getArticleImage($article['image']),
                'name' => $article['name'],
                'description' => $article['description'],
                'published_at' => DateService::getCommonFormatDate($article['published_at']),
            ];
        }

        return $formattedArticles;
    }

    public function findByCategory(
        int $categoryId,
        string $sort = 'published_at',
        string $direction = 'desc',
        int $limit = 10,
        int $offset = 0
    ): array {
        $total = $this->articleRepository->getCountByCategoryId($categoryId);

        if ($total === 0) {
            return ['items' => [], 'total' => 0];
        }

        $rows = $this->articleRepository->findByCategoryId($categoryId, $sort, $direction, $limit, $offset);

        $items = [];
        foreach ($rows as $row) {
            $items[] = [
                'id' => (int)$row['id'],
                'category_id' => (int)$row['category_id'],
                'image' => ImageService::getArticleImage($row['image'] ?? ''),
                'name' => $row['name'],
                'description' => $row['description'] ?? '',
                'content' => $row['content'],
                'views' => (int)($row['views'] ?? 0),
                'published_at' => $row['published_at']
                    ? DateService::getCommonFormatDate($row['published_at'])
                    : '',
            ];
        }

        return [
            'items' => $items,
            'total' => $total,
        ];
    }

    public function increaseViewsById(int $id): void
    {
        $this->articleRepository->increaseViewsById($id);
    }
}