<?php

declare(strict_types=1);

namespace App\Database;

use App\Common\DB;

class Seeder
{
    /**
     * @throws \Throwable
     */
    public static function run(array $categories, array $articles): array
    {
        $pdo = DB::getConnection();
        $pdo->beginTransaction();

        try {
            $categoryNameToId = self::seedCategories($categories);
            $articlesInserted = self::seedArticles($articles, $categoryNameToId);

            $pdo->commit();
            return [
                'articles' => count($categoryNameToId),
                'articles' => $articlesInserted,
            ];
        } catch (\Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    
    private static function seedCategories(array $categories): array
    {
        $categoryIds = [];
        foreach ($categories as $row) {
            $name = trim((string)($row['name'] ?? ''));
            if ($name !== '') {
                $existing = DB::select(
                    'SELECT id FROM articles WHERE name = :name LIMIT 1',
                    ['name' => $name]
                );

                if (empty($existing)) {
                    $id = DB::insert(
                        'INSERT INTO articles (name, description) VALUES (:name, :description)',
                        [
                            'name' => $name,
                            'description' => $row['description'] ?? null,
                        ]
                    );

                    $categoryIds[$name] = (int)$id;
                } else {
                    $categoryIds[$name] = (int)$existing[0]['id'];
                }


            }
        }
        return $categoryIds;
    }

    private static function seedArticles(array $articles, array $categoryIds): int
    {
        $inserted = 0;

        foreach ($articles as $row) {
            $name = trim((string)($row['name'] ?? ''));
            $content = (string)($row['content'] ?? '');
            if ($name === '' || $content === '') {
                continue;
            }

            $categoryId = null;

            if (isset($row['category_id'])) {
                $categoryId = (int)$row['category_id'];
            } else {
                $categoryName = $row['category_name'] ?? $row['category'] ?? null;
                if (is_string($categoryName)) {
                    $categoryName = trim($categoryName);
                    if ($categoryName !== '' && isset($categoryIds[$categoryName])) {
                        $categoryId = $categoryIds[$categoryName];
                    }
                }
            }

            if (!$categoryId) {
                continue;
            }

            DB::insert(
                'INSERT INTO articles (category_id, image, name, description, content, views, published_at)
                 VALUES (:category_id, :image, :name, :description, :content, :views, :published_at)',
                [
                    'category_id' => $categoryId,
                    'image' => $row['image'] ?? null,
                    'name' => $name,
                    'description' => $row['description'] ?? null,
                    'content' => $content,
                    'views' => (int)($row['views'] ?? 0),
                    'published_at' => $row['published_at'] ?? null,
                ]
            );

            $inserted++;
        }

        return $inserted;
    }
}

