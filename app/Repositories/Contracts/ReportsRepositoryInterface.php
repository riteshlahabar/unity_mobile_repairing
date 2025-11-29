<?php

namespace App\Repositories\Contracts;

interface ReportsRepositoryInterface
{
    public function getTechnicianPerformance(string $from, string $to): array;
    public function getTopDevices(string $from, string $to, int $limit = 10): array;
    public function getProblemBreakdown(string $from, string $to): array;
    public function getDeviceConditionStats(string $from, string $to): array;
    public function getWaterDamageStats(string $from, string $to): array;
    public function getPhysicalDamageStats(string $from, string $to): array;
    public function getCustomerFlow(string $from, string $to, string $dateFormat): array;
    public function getRevenueStats(string $from, string $to, string $dateFormat): array;
}
