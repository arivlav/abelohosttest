<?php

namespace App\Repositories;

interface ICategoryRepository
{
    public function all(): array;
    public function findById(int $id): array;
}