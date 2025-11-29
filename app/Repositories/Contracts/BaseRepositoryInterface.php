<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface BaseRepositoryInterface
{
    /**
     * Get all records
     * 
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Get paginated records
     * 
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Find a record by ID
     * 
     * @param int $id
     * @return Model|null
     */
    public function find(int $id): ?Model;

    /**
     * Find a record by ID or fail
     * 
     * @param int $id
     * @return Model
     */
    public function findOrFail(int $id): Model;

    /**
     * Create a new record
     * 
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model;

    /**
     * Update a record
     * 
     * @param int $id
     * @param array $data
     * @return Model
     */
    public function update(int $id, array $data): Model;

    /**
     * Delete a record
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Find record by field value
     * 
     * @param string $field
     * @param mixed $value
     * @return Model|null
     */
    public function findBy(string $field, mixed $value): ?Model;

    /**
     * Find all records by field value
     * 
     * @param string $field
     * @param mixed $value
     * @return Collection
     */
    public function findAllBy(string $field, mixed $value): Collection;
}
