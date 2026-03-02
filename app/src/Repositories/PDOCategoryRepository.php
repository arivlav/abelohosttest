<?php

namespace App\Repositories;

use App\Common\DB;

class PDOCategoryRepository implements ICategoryRepository
{

    public function all(): array
    {
        return DB::select('SELECT * FROM categories WHERE deleted_at IS NULL ORDER BY name');
    }

    public function findById(int $id): array
    {
        $rows = DB::select(
            'SELECT * FROM categories WHERE id = ? AND deleted_at IS NULL',
            [$id]
        );

        return $rows[0] ?? [];
    }
}