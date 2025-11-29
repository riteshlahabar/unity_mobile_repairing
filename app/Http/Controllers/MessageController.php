<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\JobSheet;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Repositories\Contracts\JobSheetRepositoryInterface;
use App\Services\Contracts\NotificationServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Message Controller
 * 
 * Handles message resending and manual notification triggers
 * 
 * @see \App\Services\Contracts\NotificationServiceInterface - Messaging service
 * @see \App\Repositories\Contracts\CustomerRepositoryInterface - Customer data
 * @see \App\Repositories\Contracts\JobSheetRepositoryInterface - JobSheet data
 */
class MessageController extends Controller
{
    public function __construct(
        protected NotificationServiceInterface $notification,
        protected CustomerRepositoryInterface $customerRepository,
        protected JobSheetRepositoryInterface $jobSheetRepository
    ) {}

    /**
     * Resend any message type (AJAX endpoint)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resend(Request $request)
    {
        try {
            $validated = $request->validate([
                'type' => 'required|in:welcome,device_received,repair_completed,otp,thank_you',
                'id' => 'required'
            ]);

            $result = match ($validated['type']) {
                'welcome' => $this->resendWelcome($validated['id']),
                'device_received' => $this->resendDeviceReceived($validated['id']),
                'repair_completed' => $this->resendRepairCompleted($validated['id']),
                'otp' => $this->resendOTP($validated['id']),
                'thank_you' => $this->resendThankYou($validated['id']),
                default => ['success' => false, 'message' => 'Invalid message type']
            };

            return response()->json($result);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Resend Message Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Resend welcome message
     */
    protected function resendWelcome(string $customerId): array
    {
        $customer = $this->customerRepository->findByCustomerId($customerId);
        return $this->notification->sendCustomerWelcome($customer);
    }

    /**
     * Resend device received message
     */
    protected function resendDeviceReceived(string $jobsheetId): array
    {
        $jobSheet = $this->jobSheetRepository->findByJobSheetId($jobsheetId);
        return $this->notification->sendDeviceReceived($jobSheet);
    }

    /**
     * Resend repair completed message
     */
    protected function resendRepairCompleted(string $jobsheetId): array
    {
        $jobSheet = $this->jobSheetRepository->findByJobSheetId($jobsheetId);
        return $this->notification->sendRepairCompleted($jobSheet);
    }

    /**
     * Resend OTP
     */
    protected function resendOTP(string $jobsheetId): array
    {
        $jobSheet = $this->jobSheetRepository->findByJobSheetId($jobsheetId);
        return $this->notification->sendDeliveryOTP($jobSheet);
    }

    /**
     * Resend thank you message
     */
    protected function resendThankYou(string $jobsheetId): array
    {
        $jobSheet = $this->jobSheetRepository->findByJobSheetId($jobsheetId);
        return $this->notification->sendThankYou($jobSheet);
    }

    /**
     * Send custom message (optional feature)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendCustom(Request $request)
    {
        try {
            $validated = $request->validate([
                'customer_id' => 'required|exists:customers,customer_id',
                'message' => 'required|string|max:1000',
            ]);

            $customer = $this->customerRepository->findByCustomerId($validated['customer_id']);
            
            // Send custom message (you'll need to add this method to WhatsAppService)
            // $result = $this->notification->sendCustomMessage($customer, $validated['message']);

            return response()->json([
                'success' => true,
                'message' => 'Custom message sent successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Custom Message Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function resendStatusMessage(Request $request)
{
    $validated = $request->validate([
        'jobsheet_id' => 'required',
        'status' => 'required|string',
    ]);

    $jobSheet = $this->jobSheetRepository->findByJobSheetId($validated['jobsheet_id']);

    // Map status values to message sending methods
    $status = $validated['status'];
    $result = null;

    switch ($status) {
        case 'Pending for Spare Parts':
            $result = $this->notification->sendPendingForSpareParts($jobSheet);
            break;
        case 'Not Ok Returned':
            $result = $this->notification->sendNotOkReturned($jobSheet);
            break;
        case 'Waiting for Approval':
            $result = $this->notification->sendWaitingForApproval($jobSheet);
            break;
        case 'Customer Approved':
            $result = $this->notification->sendCustomerApproved($jobSheet);
            break;
        case 'Returned':
            $result = $this->notification->sendReturned($jobSheet);
            break;
        default:
            $result = ['success' => false, 'message' => 'Invalid status for message sending'];
    }

    return response()->json($result);
}

}
