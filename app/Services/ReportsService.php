<?php

namespace App\Services;

use App\Repositories\Contracts\ReportsRepositoryInterface;

class ReportsService
{
    // Mapping of period names to MySQL date formats
    protected array $dateFormats = [
        'day' => '%Y-%m-%d',
        'month' => '%Y-%m',
        'year' => '%Y',
    ];

    public function __construct(
        protected ReportsRepositoryInterface $repository
    ) {}

    /**
     * Generate comprehensive report data
     * 
     * @param string $from
     * @param string $to
     * @param string $period (day|month|year)
     * @return array
     */
    public function generateReportData(string $from, string $to, string $period = 'day'): array
    {
        // Get date format for the specified period
        $dateFormat = $this->getDateFormat($period);

        // Fetch all statistics
        $technicianStats = $this->repository->getTechnicianPerformance($from, $to);
        $deviceStats = $this->repository->getTopDevices($from, $to, 10);
        $problemStats = $this->repository->getProblemBreakdown($from, $to);
        $conditionStats = $this->repository->getDeviceConditionStats($from, $to);
        $waterDamageStats = $this->repository->getWaterDamageStats($from, $to);
        $physicalDamageStats = $this->repository->getPhysicalDamageStats($from, $to);
        $customerFlow = $this->repository->getCustomerFlow($from, $to, $dateFormat);
        $revenueStats = $this->repository->getRevenueStats($from, $to, $dateFormat);

        // Format and return data
        return [
            'technician' => $technicianStats,
            'devices' => $deviceStats,
            'problems' => $problemStats,
            'conditions' => $conditionStats,
            'damageLabels' => ['Light', 'Full'],
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
        ];
    }

    /**
     * Get MySQL date format for a given period
     * 
     * @param string $period
     * @return string
     */
    protected function getDateFormat(string $period): string
    {
        return $this->dateFormats[$period] ?? $this->dateFormats['day'];
    }

    /**
     * Get default date range (last 30 days)
     * 
     * @return array
     */
    public function getDefaultDateRange(): array
    {
        return [
            'from' => now()->subDays(30)->toDateString(),
            'to' => now()->toDateString(),
        ];
    }

    /**
     * Validate and normalize period input
     * 
     * @param string|null $period
     * @return string
     */
    public function normalizePeriod(?string $period): string
    {
        $validPeriods = ['day', 'month', 'year'];
        
        return in_array($period, $validPeriods) ? $period : 'day';
    }
}
