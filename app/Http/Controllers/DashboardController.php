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

        // Total counts
        $totalCustomers = Customer::count();
        $totalJobSheets = JobSheet::count();

        // Status counts
        $inProgressCount = JobSheet::where('status', 'in_progress')->count();
        $completedCount = JobSheet::where('status', 'completed')->count();
        $deliveredTodayCount = JobSheet::where('status', 'delivered')
            ->whereDate('updated_at', $today)
            ->count();

        // New request (created today)
        $newRequestCount = JobSheet::whereDate('created_at', $today)->count();

        // Recent jobsheets ordered by status (In Progress → Completed → Delivered)
        $recentJobSheets = JobSheet::with('customer')
            ->orderByRaw("FIELD(status, 'in_progress', 'completed', 'delivered')")
            ->latest()
            ->take(20)
            ->get();

        return view('dashboard.index', compact(
            'totalCustomers',
            'totalJobSheets',
            'inProgressCount',
            'completedCount',
            'deliveredTodayCount',
            'newRequestCount',
            'recentJobSheets'
        ));
    }
}
