<?php

namespace App\Services;

use App\Repositories\Contracts\DashboardRepositoryInterface;
use App\Models\JobSheet;
use App\Models\Customer;

class DashboardService
{
    public function __construct(
        protected DashboardRepositoryInterface $repository
    ) {}

    /**
     * Get all dashboard statistics
     * 
     * @return array
     */
    public function getDashboardStats()
{
    // Include all relevant statuses in each count
   

    $inProgressCount = JobSheet::whereIn('status', ['in_progress', 'pending_for_spare_parts', 'waiting_for_approval', 'customer_approved', 'mark_completed'])->count();

    $completedCount = JobSheet::where('status', 'completed')->count();

    $deliveredTodayCount = JobSheet::where('status', 'delivered')->whereDate('delivered_date', today())->count();

    $recentJobSheets = JobSheet::with('customer')
        ->orderBy('created_at', 'desc')
        ->limit(20)
        ->get();

    return [
        'inProgressCount' => $inProgressCount,
        'completedCount' => $completedCount,
        'deliveredTodayCount' => $deliveredTodayCount,
        'newRequestCount' => JobSheet::whereDate('created_at', today())->count(), // if applicable
        'recentJobSheets' => $recentJobSheets,
        'totalCustomers' => Customer::count(),
        'totalJobSheets' => JobSheet::count(),
    ];
}


    /**
     * Get summary counts for dashboard cards
     * 
     * @return array
     */
    public function getSummaryCounts(): array
    {
        return [
            'totalCustomers' => $this->repository->getTotalCustomers(),
            'totalJobSheets' => $this->repository->getTotalJobSheets(),
            'inProgressCount' => $this->repository->getInProgressCount(),
            'completedCount' => $this->repository->getCompletedCount(),
            'deliveredTodayCount' => $this->repository->getDeliveredTodayCount(),
            'newRequestCount' => $this->repository->getNewRequestCount(),
        ];
    }

    /**
     * Get recent activity
     * 
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecentActivity(int $limit = 20)
    {
        return $this->repository->getRecentJobSheets($limit);
    }
}
