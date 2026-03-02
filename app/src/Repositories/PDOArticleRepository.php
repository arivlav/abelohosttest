<?php

namespace App\Repositories;

use App\Common\DB;

class PDOArticleRepository implements IArticleRepository
{

    public function findRandTreeForAllCategories(): array
    {
        return DB::select(
            'SELECT
                c.id AS category_id,
                a.id AS article_id,
                a.image,
                a.name,
                a.description,
                a.published_at
            FROM articles c
            LEFT JOIN (
                SELECT *
                FROM (
                    SELECT
                        a.*,
                        ROW_NUMBER() OVER (PARTITION BY a.category_id ORDER BY RAND()) AS rn
                    FROM articles a
                    WHERE a.deleted_at IS NULL
                ) t
                WHERE t.rn <= 3
            ) a ON a.category_id = c.id
            WHERE c.deleted_at IS NULL
            ORDER BY c.id, a.rn'
        );
    }

    public function findById(int $id): array
    {
        $rows = DB::select('SELECT * FROM articles WHERE id = ? AND deleted_at IS NULL', [$id]);
        return $rows[0] ?? [];
    }

    public function getCountByCategoryId(int $categoryId): int
    {
        return DB::count(
            'SELECT COUNT(*) AS cnt FROM articles WHERE category_id = ? AND deleted_at IS NULL',
            [$categoryId]
        );
    }

    public function findByCategoryId(
        int $categoryId,
        string $orderColumn,
        string $orderDir,
        int $limit,
        int $offset
    ): array
    {
        return DB::select(
            "SELECT id, category_id, image, name, description, content, views, published_at
             FROM articles
             WHERE category_id = ? AND deleted_at IS NULL
             ORDER BY {$orderColumn} {$orderDir}, id DESC
             LIMIT ? OFFSET ?",
            [
                $categoryId,
                $limit,
                $offset,
            ]
        );
    }

    public function increaseViewsById(int $id): void
    {
        DB::update('UPDATE articles SET views = views + 1 WHERE id = ?', [$id]);
    }
}