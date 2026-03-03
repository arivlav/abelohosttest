<?php

namespace App\Repositories;

interface IArticleRepository
{
    public function findRandTreeForCategories(): array;
    public function findById(int $id): array;
}