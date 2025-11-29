<?php

namespace App\Repositories\Contracts;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface CustomerRepositoryInterface extends BaseRepositoryInterface
{
    public function findByCustomerId(string $customerId): Customer;
    public function findByContactNo(string $contactNo): ?Customer;
    public function search(string $search, int $perPage = 20): LengthAwarePaginator;
    public function searchQuery(string $query, int $limit = 10): Collection;
    public function getTodayDeliveredOrNonDelivered(int $perPage = 20): LengthAwarePaginator;
    public function getTodayDeliveredOrNonDeliveredWithSearch(string $search, int $perPage = 20): LengthAwarePaginator;
    public function generateCustomerId(): string;
}
