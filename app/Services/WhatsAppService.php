<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\WhatsAppNotification;

class WhatsAppService
{
    protected $instanceId;
    protected $token;
    protected $apiUrl;

    public function __construct()
    {
        $this->instanceId = config('services.greenapi.instance_id');
        $this->token = config('services.greenapi.token');
        $this->apiUrl = config('services.greenapi.api_url');
    }

    /**
     * Send WhatsApp message
     */
    public function sendMessage($phoneNumber, $message)
    {
        try {
            $formattedPhone = $this->formatPhoneNumber($phoneNumber);

            $url = "{$this->apiUrl}/waInstance{$this->instanceId}/sendMessage/{$this->token}";

            $response = Http::post($url, [
                'chatId' => $formattedPhone . '@c.us',
                'message' => $message
            ]);

            Log::info('WhatsApp Message Sent', [
                'phone' => $formattedPhone,
                'response' => $response->json()
            ]);

            return [
                'success' => $response->successful(),
                'data' => $response->json()
            ];

        } catch (\Exception $e) {
            Log::error('WhatsApp Send Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send PDF file via WhatsApp using URL
     */
    public function sendFile($phoneNumber, $filePath, $fileName)
    {
        try {
            $formattedPhone = $this->formatPhoneNumber($phoneNumber);

            $url = "{$this->apiUrl}/waInstance{$this->instanceId}/sendFileByUrl/{$this->token}";

            // Generate public URL for the file
            $fileUrl = url('storage/jobsheets/' . $fileName);

            $response = Http::post($url, [
                'chatId' => $formattedPhone . '@c.us',
                'urlFile' => $fileUrl,
                'fileName' => $fileName,
                'caption' => 'Your JobSheet PDF - ' . $fileName
            ]);

            Log::info('WhatsApp File Sent', [
                'phone' => $formattedPhone,
                'file' => $fileName,
                'url' => $fileUrl,
                'response' => $response->json()
            ]);

            return [
                'success' => $response->successful(),
                'data' => $response->json()
            ];

        } catch (\Exception $e) {
            Log::error('WhatsApp File Send Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send message by notification title
     */
    public function sendByTitle($title, $phoneNumber, $data = [])
    {
        $notification = WhatsAppNotification::where('title', $title)->first();

        if (!$notification) {
            Log::error("WhatsApp notification not found: {$title}");
            return [
                'success' => false,
                'error' => 'Notification template not found'
            ];
        }

        // Replace placeholders in message
        $message = $this->replacePlaceholders($notification->message, $data);
        
        //formatted msg
       $message = preg_replace('/\\\\n/', "\n", $this->replacePlaceholders($notification->message, $data));
        return $this->sendMessage($phoneNumber, $message);
    }

    /**
     * Replace placeholders in message
     */
    protected function replacePlaceholders($message, $data)
    {
        foreach ($data as $key => $value) {
            $message = str_replace('{{' . $key . '}}', $value, $message);
        }
        return $message;
    }

    /**
     * Format phone number for WhatsApp
     */
    protected function formatPhoneNumber($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (strlen($phone) == 10) {
            $phone = '91' . $phone;
        }

        return $phone;
    }

    /**
     * Generate random OTP and store in cache for specified minutes
     */
    public function generateAndStoreOTP($jobsheetId, $minutes = 5)
    {
        $otp = rand(100000, 999999); // 6-digit OTP

        // Store OTP in cache
        Cache::put("otp_{$jobsheetId}", $otp, now()->addMinutes($minutes));

        Log::info("OTP Generated", [
            'jobsheet_id' => $jobsheetId,
            'otp' => $otp,
            'expires_in' => $minutes . ' minutes'
        ]);

        return $otp;
    }

    /**
     * Verify OTP
     */
    public function verifyOTP($jobsheetId, $inputOtp)
{
    $storedOtp = Cache::get("otp_{$jobsheetId}");

    if (!$storedOtp) {
        return [
            'success' => false,
            'message' => 'OTP expired or not found'
        ];
    }

    if ($storedOtp != $inputOtp) {
        return [
            'success' => false,
            'message' => 'Invalid OTP'
        ];
    }

    // Mark OTP as verified (store verification flag)
    Cache::put("otp_verified_{$jobsheetId}", true, now()->addMinutes(10));

    Log::info("OTP Verified Successfully", [
        'jobsheet_id' => $jobsheetId
    ]);

    return [
        'success' => true,
        'message' => 'OTP verified successfully'
    ];
}

/**
 * Check if OTP is already verified
 */
public function isOTPVerified($jobsheetId)
{
    return Cache::get("otp_verified_{$jobsheetId}", false);
}

/**
 * Clear OTP after delivery
 */
public function clearOTP($jobsheetId)
{
    Cache::forget("otp_{$jobsheetId}");
    Cache::forget("otp_verified_{$jobsheetId}");
    
    Log::info("OTP Cleared", [
        'jobsheet_id' => $jobsheetId
    ]);
}
}
