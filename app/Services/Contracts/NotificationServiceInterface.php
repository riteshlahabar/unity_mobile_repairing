<?php

namespace App\Services\Contracts;

use App\Models\JobSheet;
use App\Models\Customer; 

interface NotificationServiceInterface
{
    // Customer notifications
    public function sendCustomerWelcome(Customer $customer): array;
    
    // JobSheet notifications
    public function sendDeviceReceived(JobSheet $jobSheet): array;
    public function sendRepairCompleted(JobSheet $jobSheet): array;
    public function sendThankYou(JobSheet $jobSheet): array;
    
    // OTP related
    public function sendDeliveryOTP(JobSheet $jobSheet): array;
    public function verifyDeliveryOTP(JobSheet $jobSheet, string $otp): array;
    public function isOTPVerified(string $jobsheetId): bool;
    public function clearOTP(JobSheet $jobSheet): void;
}
