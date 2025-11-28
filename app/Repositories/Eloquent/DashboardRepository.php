<?php

namespace App\Repositories\Eloquent;

use App\Models\Customer;
use App\Models\JobSheet;
use App\Repositories\Contracts\DashboardRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

class DashboardRepository implements DashboardRepositoryInterface
{
    /**
     * Get total number of customers
     * 
     * @return int
     */
    public function getTotalCustomers(): int
    {
        return Customer::count();
    }

    /**
     * Get total number of jobsheets
     * 
     * @return int
     */
    public function getTotalJobSheets(): int
    {
        return JobSheet::count();
    }

    /**
     * Get count of jobsheets in progress
     * 
     * @return int
     */
    public function getInProgressCount(): int
    {
        return JobSheet::where('status', 'in_progress')->count();
    }

    /**
     * Get count of completed jobsheets
     * 
     * @return int
     */
    public function getCompletedCount(): int
    {
        return JobSheet::where('status', 'completed')->count();
    }

    /**
     * Get count of jobsheets delivered today
     * 
     * @return int
     */
    public function getDeliveredTodayCount(): int
    {
        $today = Carbon::today();
        
        return JobSheet::where('status', 'delivered')
            ->whereDate('updated_at', $today)
            ->count();
    }

    /**
     * Get count of new requests created today
     * 
     * @return int
     */
    public function getNewRequestCount(): int
    {
        $today = Carbon::today();
        
        return JobSheet::whereDate('created_at', $today)->count();
    }

    /**
     * Get recent jobsheets ordered by status priority
     * Priority: In Progress → Completed → Delivered
     * 
     * @param int $limit
     * @return Collection
     */
    public function getRecentJobSheets(int $limit = 20): Collection
    {
        return JobSheet::with('customer')
            ->orderByRaw("FIELD(status, 'in_progress', 'completed', 'delivered')")
            ->latest()
            ->take($limit)
            ->get();
    }
}
