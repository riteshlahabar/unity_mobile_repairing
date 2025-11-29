<?php

namespace App\Services\Contracts;

interface RevenueServiceInterface
{
    public function verifyPin(string $pin): array;  // ← MUST MATCH EXACTLY
    
    public function getRevenueData(): array;
    public function updateProfit(int $jobsheetId, float $serviceCharge, float $sparePartsCharge, float $otherCharge): array;
}
