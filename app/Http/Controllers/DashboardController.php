<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\JobSheet;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // New Requests (Created Today)
        $newRequestCount = JobSheet::whereDate('created_at', $today)->count();

        // In Progress
        $inProgressCount = JobSheet::where('status', 'in_progress')->count();

        // Completed
        $completedCount = JobSheet::where('status', 'completed')->count();

        // Delivered Today
        $deliveredTodayCount = JobSheet::where('status', 'delivered')
            ->whereDate('updated_at', $today)
            ->count();

        // Recent JobSheets with Customer (for table)
        $recentJobSheets = JobSheet::with('customer')
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.index', compact(
            'newRequestCount',
            'inProgressCount',
            'completedCount',
            'deliveredTodayCount',
            'recentJobSheets'
        ));
    }
}
