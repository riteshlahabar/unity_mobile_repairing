<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\MobileDetailRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MobileDetailRepository implements MobileDetailRepositoryInterface
{
    /**
     * Check if a record exists in a table (case-insensitive)
     * 
     * @param string $table
     * @param string $name
     * @return bool
     */
    public function exists(string $table, string $name): bool
    {
        return DB::table($table)
            ->whereRaw('LOWER(name) = ?', [strtolower($name)])
            ->exists();
    }

    /**
     * Store a new record in a table
     * 
     * @param string $table
     * @param string $name
     * @return bool
     */
    public function store(string $table, string $name): bool
    {
        return DB::table($table)->insert([
            'name' => $name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Fetch all records from a table ordered by name
     * 
     * @param string $table
     * @return Collection
     */
    public function fetchAll(string $table): Collection
    {
        return DB::table($table)
            ->orderBy('name')
            ->pluck('name');
    }
}
