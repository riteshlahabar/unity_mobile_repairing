<?php

namespace App\Services\Contracts;

interface RevenueServiceInterface
{
<<<<<<< HEAD
    public function verifyPin(string $pin): array;  // ← MUST MATCH EXACTLY
    
    public function getRevenueData(): array;
=======
    public function verifyPin(string $pin): array;
    
    public function getRevenueData(): array;
    
>>>>>>> 0963cebdc0528a837022693382951a181cdac698
    public function updateProfit(int $jobsheetId, float $serviceCharge, float $sparePartsCharge, float $otherCharge): array;
}
