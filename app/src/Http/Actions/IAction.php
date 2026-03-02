<?php

namespace App\Http\Actions;

interface IAction
{
    public function execute(?int $id = null): array;
}