<?php

namespace App\Repositories;

interface IArticleRepository
{
    public function findRandTreeForAllCategories(): array;
    public function findById(int $id): array;
}