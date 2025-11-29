<?php

namespace App\Repositories\Eloquent;

use App\Models\JobSheet;
use App\Repositories\Contracts\JobSheetRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class JobSheetRepository implements JobSheetRepositoryInterface
{
    public function __construct(
        protected JobSheet $model
    ) {}

    /**
     * Get paginated jobsheets
     */
    public function paginate(int $perPage = 20): LengthAwarePaginator
    {
        return $this->model->with('customer')->latest()->paginate($perPage);
    }

    /**
     * Search jobsheets by multiple fields
     */
    public function search(string $search, int $perPage = 20): LengthAwarePaginator
    {
        return $this->model->with('customer')
            ->where(function($q) use ($search) {
                $q->where('jobsheet_id', 'LIKE', "%{$search}%")
                  ->orWhere('company', 'LIKE', "%{$search}%")
                  ->orWhere('model', 'LIKE', "%{$search}%")
                  ->orWhere('color', 'LIKE', "%{$search}%")
                  ->orWhere('series', 'LIKE', "%{$search}%")
                  ->orWhere('problem_description', 'LIKE', "%{$search}%")
                  ->orWhere('imei', 'LIKE', "%{$search}%")
                  ->orWhere('technician', 'LIKE', "%{$search}%")
<<<<<<< HEAD
                  ->orWhere('status', 'LIKE', "%{$search}%")
=======
>>>>>>> 0963cebdc0528a837022693382951a181cdac698
                  ->orWhereHas('customer', function($subQ) use ($search) {
                      $subQ->where('full_name', 'LIKE', "%{$search}%")
                           ->orWhere('customer_id', 'LIKE', "%{$search}%")
                           ->orWhere('contact_no', 'LIKE', "%{$search}%")
                           ->orWhere('whatsapp_no', 'LIKE', "%{$search}%");
                  });
            })
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Find jobsheet by jobsheet_id (string identifier)
     */
    public function findByJobSheetId(string $jobsheetId): ?JobSheet
    {
        return $this->model->with(['customer', 'devicePhotos'])
            ->where('jobsheet_id', $jobsheetId)
            ->first();
    }

    /**
     * Get jobsheet by numeric ID (primary key)
     */
    public function getById(int $id): ?JobSheet
    {
        return $this->model->with('customer')->find($id);
    }

    /**
     * Alias for findByJobSheetId
     */
    public function getByJobsheetId(string $jobsheetId): ?JobSheet
    {
        return $this->findByJobSheetId($jobsheetId);
    }

    /**
     * Create new jobsheet
     */
    public function create(array $data): JobSheet
    {
        return $this->model->create($data);
    }

    /**
     * Update jobsheet by jobsheet_id
     */
    public function update(string $jobsheetId, array $data): JobSheet
    {
        $jobSheet = $this->model->where('jobsheet_id', $jobsheetId)->firstOrFail();
        $jobSheet->update($data);
        return $jobSheet->fresh();
    }

    /**
     * Delete jobsheet by jobsheet_id
     */
    public function delete(string $jobsheetId): bool
    {
        $jobSheet = $this->findByJobSheetId($jobsheetId);
        return $jobSheet ? $jobSheet->delete() : false;
    }

    /**
     * Update jobsheet status
     */
    public function updateStatus(string $jobsheetId, string $status): JobSheet
    {
        $jobSheet = $this->model->where('jobsheet_id', $jobsheetId)->firstOrFail();
        $jobSheet->update(['status' => $status]);
        return $jobSheet->fresh();
    }

    /**
     * Generate unique jobsheet ID
     */
    public function generateJobSheetId(): string
    {
        return JobSheet::generateJobSheetId();
    }

    /**
     * Get all delivered jobsheets
     */
    public function getDeliveredJobsheets()
    {
        return $this->model->with('customer')
            ->where('status', 'delivered')
            ->select('id', 'jobsheet_id', 'customer_id', 'company', 'model', 'problem_description', 'estimated_cost')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get jobsheets by status
     */
    public function getByStatus(string $status)
    {
        return $this->model->with('customer')
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get all jobsheets with customer relationship (paginated)
     */
    public function getAllWithCustomer(int $perPage = 20): LengthAwarePaginator
    {
        return $this->model->with('customer')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
