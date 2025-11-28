<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\FestivalMessageRepositoryInterface;
use App\Services\FestivalMessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Festival Message Controller
 * 
 * Handles bulk festival message sending to customers
 * 
 * @see \App\Repositories\Contracts\FestivalMessageRepositoryInterface - Data access
 * @see \App\Services\FestivalMessageService - Business logic
 * 
 * Dependencies injected via constructor:
 * - FestivalMessageRepositoryInterface $repository
 * - FestivalMessageService $service
 */
class FestivalMessageController extends Controller
{
    public function __construct(
        protected FestivalMessageRepositoryInterface $repository,
        protected FestivalMessageService $service
    ) {}

    /**
     * Show festival message page with statistics
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get dashboard stats
        $stats = $this->service->getDashboardStats();
        
        // Get recent messages
        $messages = $this->repository->getRecentMessages(20);
        
        return view('festival.index', [
            'totalCustomers' => $stats['totalCustomers'],
            'sentMessages' => $stats['sentMessages'],
            'failedMessages' => $stats['failedMessages'],
            'messages' => $messages,
        ]);
    }

    /**
     * Send festival messages to customers
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessages(Request $request)
    {
        try {
            $validated = $request->validate([
                'message' => 'required|string|max:1000',
                'send_to' => 'required|in:all,selected',
                'customer_ids' => 'required_if:send_to,selected|array',
            ]);

            $result = $this->service->sendFestivalMessages(
                $validated['message'],
                $validated['send_to'],
                $validated['customer_ids'] ?? []
            );

            return response()->json($result);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Festival Message Send Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get customers for selection (AJAX)
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomers()
    {
        $customers = $this->service->getCustomersForSelection();
        return response()->json($customers);
    }
}
