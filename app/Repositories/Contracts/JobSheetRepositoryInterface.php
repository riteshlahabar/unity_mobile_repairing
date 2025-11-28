<?php

namespace App\Repositories\Contracts;

use App\Models\JobSheet;
use Illuminate\Pagination\LengthAwarePaginator;

interface JobSheetRepositoryInterface
{
    public function paginate(int $perPage = 20): LengthAwarePaginator;
    public function search(string $search, int $perPage = 20): LengthAwarePaginator;
    public function findByJobSheetId(string $jobsheetId): ?JobSheet;
    public function create(array $data): JobSheet;
    public function update(string $jobsheetId, array $data): JobSheet;
    public function delete(string $jobsheetId): bool;
    public function updateStatus(string $jobsheetId, string $status): JobSheet;
    public function generateJobSheetId(): string;
}
