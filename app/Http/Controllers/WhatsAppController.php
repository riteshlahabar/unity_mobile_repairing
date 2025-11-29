<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\FestivalMessageRepositoryInterface;
use App\Services\WhatsAppNotificationService;
use App\Services\FestivalMessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Customer;

/**
 * WhatsApp Controller
 * 
 * Handles WhatsApp notification templates and festival messaging
 * 
 * @see \App\Services\WhatsAppNotificationService - Notification templates
 * @see \App\Services\FestivalMessageService - Festival messaging
 */
class WhatsAppController extends Controller
{
    public function __construct(
        protected WhatsAppNotificationService $notificationService,
        protected FestivalMessageService $festivalService,
        protected FestivalMessageRepositoryInterface $festivalRepository
    ) {}

    // ========================================
    // SERVICE NOTIFICATION METHODS
    // ========================================

    /**
     * Show service notification page
     * 
     * @return \Illuminate\View\View
     */
    public function service()
    {
        $notifications = $this->notificationService->getAllNotifications();
        return view('whatsapp.service', compact('notifications'));
    }

    /**
     * Store notification template
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeNotification(Request $request)
    {
        try {
            $validated = $request->validate([
                'notification_title' => 'required|string|max:255',
                'notification_message' => 'required|string',
            ]);

            $result = $this->notificationService->createNotification([
                'title' => $validated['notification_title'],
                'message' => $validated['notification_message'],
            ]);

            return response()->json($result, 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update notification template
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateNotification(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'notification_title' => 'required|string|max:255',
                'notification_message' => 'required|string',
            ]);

            $result = $this->notificationService->updateNotification($id, [
                'title' => $validated['notification_title'],
                'message' => $validated['notification_message'],
            ]);

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete notification template
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteNotification($id)
    {
        try {
            $result = $this->notificationService->deleteNotification($id);
            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // ========================================
    // FESTIVAL MESSAGE METHODS
    // ========================================

    /**
     * Show festival message page
     * 
     * @return \Illuminate\View\View
     */
    public function festival()
    {
        $stats = $this->festivalService->getDashboardStats();
        $messages = $this->festivalRepository->getRecentMessages(20);
        
        return view('whatsapp.festival', [
            'totalCustomers' => $stats['totalCustomers'],
            'sentMessages' => $stats['sentMessages'],
            'failedMessages' => $stats['failedMessages'],
            'messages' => $messages,
        ]);
    }

    /**
     * Send festival messages
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendFestivalMessages(Request $request)
    {
        \Log::info('Festival send request received:', $request->all());

        try {
            $rules = [
                'send_to' => 'required|in:all,selected',
                'message' => 'required|string|max:1000',
                'campaign_name' => 'nullable|string|max:255',
            ];

            if ($request->input('send_to') === 'selected') {
                $rules['from_date'] = 'required|date';
                $rules['to_date'] = 'required|date';
            }

            $validated = $request->validate($rules);

            // Build customer list
            $customerIds = [];
            if ($validated['send_to'] === 'all') {
                $customerIds = Customer::whereNotNull('whatsapp_no')->pluck('id')->toArray();
            } else {
                $customerIds = Customer::whereNotNull('whatsapp_no')
                    ->whereBetween('created_at', [
                        $validated['from_date'],
                        $validated['to_date'],
                    ])->pluck('id')->toArray();
            }

            $campaignName = $request->input('campaign_name', null);

            // IMPORTANT: call service, no hardcoded numbers here
            $result = $this->festivalService->sendFestivalMessages(
                message: $validated['message'],
                sendTo: $validated['send_to'],
                customerIds: $customerIds,
                campaignName: $campaignName
            );

            return response()->json($result, 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Festival Message Send Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get customers for selection (AJAX)
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFestivalCustomers()
    {
        $customers = $this->festivalService->getCustomersForSelection();
        return response()->json($customers);
    }

    /**
     * Get customer count by date range (AJAX)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomerCountByDate(Request $request)
    {
        try {
            $request->validate([
                'from_date' => 'required|date',
                'to_date' => 'required|date',
            ]);

            $count = $this->festivalService->getCustomerCountByDateRange(
                $request->from_date,
                $request->to_date
            );

            return response()->json(['count' => $count]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
