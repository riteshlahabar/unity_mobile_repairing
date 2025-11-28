<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

/**
 * Dashboard Controller
 * 
 * Handles dashboard statistics and overview display
 * 
 * @see \App\Repositories\Contracts\DashboardRepositoryInterface - Data access
 * @see \App\Services\DashboardService - Business logic
 * 
 * Dependencies injected via constructor:
 * - DashboardService $service
 */
class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $service
    ) {}

    /**
     * Display dashboard with statistics
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get all dashboard statistics
        $stats = $this->service->getDashboardStats();

        return view('dashboard.index', [
            'totalCustomers' => $stats['totalCustomers'],
            'totalJobSheets' => $stats['totalJobSheets'],
            'inProgressCount' => $stats['inProgressCount'],
            'completedCount' => $stats['completedCount'],
            'deliveredTodayCount' => $stats['deliveredTodayCount'],
            'newRequestCount' => $stats['newRequestCount'],
            'recentJobSheets' => $stats['recentJobSheets'],
        ]);
    }

    /**
     * Get dashboard stats as JSON (for AJAX/API)
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function stats()
    {
        $stats = $this->service->getSummaryCounts();
        return response()->json($stats);
    }

    /**
     * Get recent activity as JSON (for AJAX refresh)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function recentActivity(Request $request)
    {
        $limit = $request->input('limit', 20);
        $activity = $this->service->getRecentActivity($limit);
        
        return response()->json([
            'success' => true,
            'data' => $activity
        ]);
    }
}
