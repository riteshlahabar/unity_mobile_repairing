<?php

namespace App\Repositories\Eloquent;

use App\Models\Customer;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface
{
    public function __construct(Customer $model)
    {
        parent::__construct($model);
    }

    public function findByCustomerId(string $customerId): Customer
    {
        return $this->model->where('customer_id', $customerId)->firstOrFail();
    }

    public function findByContactNo(string $contactNo): ?Customer
    {
        return $this->model->where('contact_no', $contactNo)->first();
    }

    public function search(string $search, int $perPage = 20): LengthAwarePaginator
    {
        return $this->model
            ->where('customer_id', 'LIKE', "%{$search}%")
            ->orWhere('full_name', 'LIKE', "%{$search}%")
            ->orWhere('contact_no', 'LIKE', "%{$search}%")
            ->orWhere('whatsapp_no', 'LIKE', "%{$search}%")
            ->orWhere('address', 'LIKE', "%{$search}%")
            ->latest()
            ->paginate($perPage);
    }

    public function searchQuery(string $query, int $limit = 10): Collection
    {
        return $this->model
            ->where('full_name', 'LIKE', "%{$query}%")
            ->orWhere('customer_id', 'LIKE', "%{$query}%")
            ->orWhere('contact_no', 'LIKE', "%{$query}%")
            ->orWhere('whatsapp_no', 'LIKE', "%{$query}%")
            ->limit($limit)
            ->get();
    }

    public function getTodayDeliveredOrNonDelivered(int $perPage = 20): LengthAwarePaginator
    {
        $today = Carbon::today();

        return $this->model
            ->with(['jobSheets' => function($q) {
                $q->latest();
            }])
            ->where(function($q) use ($today) {
                // Customers with jobsheets delivered today
                $q->whereHas('jobSheets', function($subQ) use ($today) {
                    $subQ->where('status', 'delivered')
                         ->whereDate('updated_at', $today);
                })
                // OR customers without any delivered jobsheets
                ->orWhereDoesntHave('jobSheets', function($subQ) {
                    $subQ->where('status', 'delivered');
                })
                // OR customers with no jobsheets at all
                ->orWhereDoesntHave('jobSheets');
            })
            ->latest()
            ->paginate($perPage);
    }

    public function getTodayDeliveredOrNonDeliveredWithSearch(string $search, int $perPage = 20): LengthAwarePaginator
    {
        $today = Carbon::today();

        return $this->model
            ->with(['jobSheets' => function($q) {
                $q->latest();
            }])
            ->where(function($q) use ($search) {
                $q->where('customer_id', 'LIKE', "%{$search}%")
                  ->orWhere('full_name', 'LIKE', "%{$search}%")
                  ->orWhere('contact_no', 'LIKE', "%{$search}%")
                  ->orWhere('whatsapp_no', 'LIKE', "%{$search}%")
                  ->orWhere('address', 'LIKE', "%{$search}%");
            })
            ->where(function($q) use ($today) {
                $q->whereHas('jobSheets', function($subQ) use ($today) {
                    $subQ->where('status', 'delivered')
                         ->whereDate('updated_at', $today);
                })
                ->orWhereDoesntHave('jobSheets', function($subQ) {
                    $subQ->where('status', 'delivered');
                })
                ->orWhereDoesntHave('jobSheets');
            })
            ->latest()
            ->paginate($perPage);
    }

    public function generateCustomerId(): string
    {
        return Customer::generateCustomerId();
    }
}
