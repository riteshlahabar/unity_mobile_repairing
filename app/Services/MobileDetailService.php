<?php

namespace App\Services;

use App\Repositories\Contracts\MobileDetailRepositoryInterface;
use Illuminate\Support\Collection;

class MobileDetailService
{
    // Define valid tables to prevent SQL injection
    protected array $validTables = [
        'company' => 'companies',
        'model' => 'models',
        'color' => 'colors',
        'series' => 'series',
        'technician' => 'technicians',
    ];

    public function __construct(
        protected MobileDetailRepositoryInterface $repository
    ) {}

    /**
     * Store a new entity (company, model, color, etc.)
     * 
     * @param string $type (company, model, color, series, technician)
     * @param string $name
     * @return array
     */
    public function storeEntity(string $type, string $name): array
    {
        // Validate table type
        if (!isset($this->validTables[$type])) {
            return [
                'success' => false,
                'message' => 'Invalid entity type'
            ];
        }

        $table = $this->validTables[$type];

        // Check if already exists
        if ($this->repository->exists($table, $name)) {
            return [
                'success' => false,
                'message' => ucfirst($type) . ' already exists'
            ];
        }

        // Store the entity
        $this->repository->store($table, $name);

        return [
            'success' => true,
            'message' => ucfirst($type) . ' added successfully'
        ];
    }

    /**
     * Fetch all entities of a specific type
     * 
     * @param string $type
     * @return Collection
     */
    public function fetchEntities(string $type): Collection
    {
        if (!isset($this->validTables[$type])) {
            return collect([]);
        }

        return $this->repository->fetchAll($this->validTables[$type]);
    }

    /**
     * Get all valid entity types
     * 
     * @return array
     */
    public function getValidTypes(): array
    {
        return array_keys($this->validTables);
    }
}
