<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface MobileDetailRepositoryInterface
{
    public function exists(string $table, string $name): bool;
    public function store(string $table, string $name): bool;
    public function fetchAll(string $table): Collection;
}
