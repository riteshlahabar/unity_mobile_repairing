<?php

namespace App\Http\Controllers;

use App\Services\ReportsService;
use Illuminate\Http\Request;

/**
 * Reports Controller
 * 
 * Handles analytics and reporting for jobsheets and customers
 * 
 * @see \App\Repositories\Contracts\ReportsRepositoryInterface - Data access
 * @see \App\Services\ReportsService - Business logic
 * 
 * Dependencies injected via constructor:
 * - ReportsService $service
 */
class ReportsController extends Controller
{
    public function __construct(
        protected ReportsService $service
    ) {}

    /**
     * Show reports page
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('reports.index');
    }

    /**
     * Get report data (AJAX endpoint)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Request $request)
    {
        // Get date range (use defaults if not provided)
        $defaults = $this->service->getDefaultDateRange();
        $from = $request->input('from', $defaults['from']);
        $to = $request->input('to', $defaults['to']);
        
        // Get and normalize period
        $period = $this->service->normalizePeriod($request->input('period'));

        // Generate report data
        $reportData = $this->service->generateReportData($from, $to, $period);

        return response()->json($reportData);
    }
}
