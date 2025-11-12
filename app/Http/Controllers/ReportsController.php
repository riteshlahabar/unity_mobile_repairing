<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\JobSheet;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function data(Request $request)
    {
        $from = $request->input('from', now()->subDays(30)->toDateString());
        $to = $request->input('to', now()->toDateString());
        $period = $request->input('period', 'day'); // day|month|year

        // Technician Performance: grouped job count by technician name
        $techStats = JobSheet::whereBetween('created_at', [$from, $to])
            ->whereNotNull('technician')
            ->selectRaw('technician, COUNT(*) as count')
            ->groupBy('technician')
            ->pluck('count', 'technician')
            ->toArray();

        // Top Devices: grouped by company and model
        $deviceStats = JobSheet::whereBetween('created_at', [$from, $to])
            ->selectRaw("CONCAT(company, ' ', model) as device, COUNT(*) as count")
            ->groupBy('company', 'model')
            ->orderByDesc('count')
            ->limit(10)
            ->pluck('count', 'device')
            ->toArray();

        // Problem Breakdown: based on status flags (dead/damage/on)
        $problemStats = [];
        $problemStats['Dead'] = JobSheet::whereBetween('created_at', [$from, $to])
            ->where('status_dead', true)->count();
        $problemStats['Damaged'] = JobSheet::whereBetween('created_at', [$from, $to])
            ->where('status_damage', true)->count();
        $problemStats['On with Problem'] = JobSheet::whereBetween('created_at', [$from, $to])
            ->where('status_on', true)->count();
        $problemStats['Others'] = JobSheet::whereBetween('created_at', [$from, $to])
            ->where('status_dead', false)
            ->where('status_damage', false)
            ->where('status_on', false)
            ->count();

        // Device Condition: fresh, shop_return, other
        $conditionStats = JobSheet::whereBetween('created_at', [$from, $to])
            ->selectRaw('device_condition, COUNT(*) as count')
            ->groupBy('device_condition')
            ->pluck('count', 'device_condition')
            ->toArray();

        // Water Damage: only "lite" and "full" (as per your request)
        $waterDamageStats = JobSheet::whereBetween('created_at', [$from, $to])
            ->whereIn('water_damage', ['lite', 'full'])
            ->selectRaw('water_damage, COUNT(*) as count')
            ->groupBy('water_damage')
            ->pluck('count', 'water_damage')
            ->toArray();

        // Physical Damage: only "lite" and "full"
        $physicalDamageStats = JobSheet::whereBetween('created_at', [$from, $to])
            ->whereIn('physical_damage', ['lite', 'full'])
            ->selectRaw('physical_damage, COUNT(*) as count')
            ->groupBy('physical_damage')
            ->pluck('count', 'physical_damage')
            ->toArray();

        // Date format based on period
        $dateFormat = [
            'day' => '%Y-%m-%d',
            'month' => '%Y-%m',
            'year' => '%Y',
        ][$period];

        // Customer Flow: new customers by period
        $customerFlow = Customer::whereBetween('created_at', [$from, $to])
            ->selectRaw("DATE_FORMAT(created_at, '$dateFormat') AS label, COUNT(*) as count")
            ->groupBy('label')
            ->orderBy('label')
            ->pluck('count', 'label')
            ->toArray();

        // Revenue: sum of advance payments (or you can use balance/estimated_cost)
        $revenueStats = JobSheet::whereBetween('created_at', [$from, $to])
            ->selectRaw("DATE_FORMAT(created_at, '$dateFormat') AS label, SUM(advance) as sum")
            ->groupBy('label')
            ->orderBy('label')
            ->pluck('sum', 'label')
            ->toArray();

        return response()->json([
            'technician' => $techStats,
            'devices' => $deviceStats,
            'problems' => $problemStats,
            'conditions' => $conditionStats,
            'damageLabels' => ['Light', 'Full'], // Match with "lite" and "full" from DB
            'waterDamage' => [
                $waterDamageStats['lite'] ?? 0,
                $waterDamageStats['full'] ?? 0
            ],
            'physicalDamage' => [
                $physicalDamageStats['lite'] ?? 0,
                $physicalDamageStats['full'] ?? 0
            ],
            'customerFlowLabels' => array_keys($customerFlow),
            'customerFlowData' => array_values($customerFlow),
            'revenueLabels' => array_keys($revenueStats),
            'revenueData' => array_values($revenueStats),
        ]);
    }
}
