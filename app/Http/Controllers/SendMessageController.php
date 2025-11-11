<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\JobSheet;
use App\Services\WhatsAppService;
use App\Services\JobSheetPdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SendMessageController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Send Customer Welcome Message
     */
    public function sendCustomerWelcome(Customer $customer)
    {
        try {
            $this->whatsappService->sendByTitle('Customer Welcome', $customer->whatsapp_no, [
                'customer_name' => $customer->full_name,
                'customer_id' => $customer->customer_id,
            ]);

            Log::info('Customer Welcome Message Sent', [
                'customer_id' => $customer->customer_id,
                'phone' => $customer->whatsapp_no
            ]);

            return [
                'success' => true,
                'message' => 'Welcome message sent successfully'
            ];

        } catch (\Exception $e) {
            Log::error('Customer Welcome Message Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Send Device Received Message with PDF
     */
    /**
 * Send Device Received Message with PDF
 */
public function sendDeviceReceived(JobSheet $jobSheet)
{
    try {
        // Generate PDF
        $pdfService = new JobSheetPdfService();
        $pdfData = $pdfService->generate($jobSheet);

        // Send WhatsApp Message
        $this->whatsappService->sendByTitle('Device Received', $jobSheet->customer->whatsapp_no, [
            'customer_name' => $jobSheet->customer->full_name,
            'customer_id' => $jobSheet->customer->customer_id,
            'jobsheet_id' => $jobSheet->jobsheet_id,
            'device' => $jobSheet->company . ' ' . $jobSheet->model,
            'problem' => $jobSheet->problem_description,
            'estimated_cost' => number_format($jobSheet->estimated_cost, 2),
            'advance' => number_format($jobSheet->advance, 2),
            'balance' => number_format($jobSheet->balance, 2),
        ]);

        // Send PDF via WhatsApp using URL
        $this->whatsappService->sendFile(
            $jobSheet->customer->whatsapp_no,
            $pdfData['path'], // Still pass path for reference
            $pdfData['name']
        );

        Log::info('Device Received Message Sent', [
            'jobsheet_id' => $jobSheet->jobsheet_id,
            'phone' => $jobSheet->customer->whatsapp_no,
            'pdf' => $pdfData['name'],
            'pdf_url' => $pdfData['url']
        ]);

        return [
            'success' => true,
            'message' => 'Device received message and PDF sent successfully'
        ];

    } catch (\Exception $e) {
        Log::error('Device Received Message Error: ' . $e->getMessage());
        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
}


    /**
     * Send Repair Completed Message
     */
    public function sendRepairCompleted(JobSheet $jobSheet)
    {
        try {
            $this->whatsappService->sendByTitle('Repair Completed', $jobSheet->customer->whatsapp_no, [
                'customer_name' => $jobSheet->customer->full_name,
                'customer_id' => $jobSheet->customer->customer_id,
                'jobsheet_id' => $jobSheet->jobsheet_id,
                'device' => $jobSheet->company . ' ' . $jobSheet->model,
                'balance' => number_format($jobSheet->balance, 2),
            ]);

            Log::info('Repair Completed Message Sent', [
                'jobsheet_id' => $jobSheet->jobsheet_id,
                'phone' => $jobSheet->customer->whatsapp_no
            ]);

            return [
                'success' => true,
                'message' => 'Repair completed message sent successfully'
            ];

        } catch (\Exception $e) {
            Log::error('Repair Completed Message Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Generate and Send OTP for Delivery
     */
    public function sendDeliveryOTP(JobSheet $jobSheet)
    {
        try {
            // Check if already delivered
            if ($jobSheet->status === 'delivered') {
                return [
                    'success' => false,
                    'message' => 'JobSheet already delivered'
                ];
            }

            // Generate OTP and store in cache for 5 minutes
            $otp = $this->whatsappService->generateAndStoreOTP($jobSheet->jobsheet_id, 5);

            // Send OTP WhatsApp Message
            $this->whatsappService->sendByTitle('OTP Notification', $jobSheet->customer->whatsapp_no, [
                'customer_name' => $jobSheet->customer->full_name,
                'otp' => $otp,
                'jobsheet_id' => $jobSheet->jobsheet_id,
            ]);

            Log::info('Delivery OTP Sent', [
                'jobsheet_id' => $jobSheet->jobsheet_id,
                'phone' => $jobSheet->customer->whatsapp_no,
                'otp' => $otp
            ]);

            return [
                'success' => true,
                'message' => 'OTP sent to customer WhatsApp (valid for 5 minutes)',
                'jobsheet_id' => $jobSheet->jobsheet_id
            ];

        } catch (\Exception $e) {
            Log::error('Delivery OTP Send Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Verify OTP for Delivery
     */
    /**
 * Verify OTP for Delivery
 */
public function verifyDeliveryOTP(JobSheet $jobSheet, $otp)
{
    try {
        // Check if already verified
        if ($this->whatsappService->isOTPVerified($jobSheet->jobsheet_id)) {
            return [
                'success' => true,
                'message' => 'OTP already verified'
            ];
        }

        // Verify OTP
        $verification = $this->whatsappService->verifyOTP($jobSheet->jobsheet_id, $otp);

        if (!$verification['success']) {
            return [
                'success' => false,
                'message' => $verification['message']
            ];
        }

        Log::info('Delivery OTP Verified', [
            'jobsheet_id' => $jobSheet->jobsheet_id
        ]);

        return [
            'success' => true,
            'message' => 'OTP verified successfully'
        ];

    } catch (\Exception $e) {
        Log::error('OTP Verification Error: ' . $e->getMessage());
        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
}

/**
 * Check if OTP is verified
 */
public function isOTPVerified(JobSheet $jobSheet)
{
    return $this->whatsappService->isOTPVerified($jobSheet->jobsheet_id);
}

/**
 * Clear OTP after delivery
 */
public function clearOTP(JobSheet $jobSheet)
{
    $this->whatsappService->clearOTP($jobSheet->jobsheet_id);
}
    /**
     * Send Thank You Message After Delivery
     */
    public function sendThankYou(JobSheet $jobSheet)
    {
        try {
            $this->whatsappService->sendByTitle('Thank You Message', $jobSheet->customer->whatsapp_no, [
                'customer_name' => $jobSheet->customer->full_name,
                'customer_id' => $jobSheet->customer->customer_id,
                'jobsheet_id' => $jobSheet->jobsheet_id,
            ]);

            Log::info('Thank You Message Sent', [
                'jobsheet_id' => $jobSheet->jobsheet_id,
                'phone' => $jobSheet->customer->whatsapp_no
            ]);

            return [
                'success' => true,
                'message' => 'Thank you message sent successfully'
            ];

        } catch (\Exception $e) {
            Log::error('Thank You Message Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Resend any message type
     */
    public function resendMessage(Request $request)
    {
        try {
            $validated = $request->validate([
                'type' => 'required|in:welcome,device_received,repair_completed,otp,thank_you',
                'id' => 'required'
            ]);

            switch ($validated['type']) {
                case 'welcome':
                    $customer = Customer::where('customer_id', $validated['id'])->firstOrFail();
                    return response()->json($this->sendCustomerWelcome($customer));

                case 'device_received':
                    $jobSheet = JobSheet::where('jobsheet_id', $validated['id'])->firstOrFail();
                    return response()->json($this->sendDeviceReceived($jobSheet));

                case 'repair_completed':
                    $jobSheet = JobSheet::where('jobsheet_id', $validated['id'])->firstOrFail();
                    return response()->json($this->sendRepairCompleted($jobSheet));

                case 'otp':
                    $jobSheet = JobSheet::where('jobsheet_id', $validated['id'])->firstOrFail();
                    return response()->json($this->sendDeliveryOTP($jobSheet));

                case 'thank_you':
                    $jobSheet = JobSheet::where('jobsheet_id', $validated['id'])->firstOrFail();
                    return response()->json($this->sendThankYou($jobSheet));

                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid message type'
                    ], 400);
            }

        } catch (\Exception $e) {
            Log::error('Resend Message Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
