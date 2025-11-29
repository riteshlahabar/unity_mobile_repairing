<?php

namespace App\Http\Controllers;

use App\Services\FestivalMessageService;
use App\Models\Customer;
use Illuminate\Http\Request;

/**
 * FestivalMessageController
 *
 * Responsibility: Handle HTTP requests and delegate to FestivalMessageService
 * Single Responsibility: Request handling and response formatting only
 */
class FestivalMessageController extends Controller
{
    protected FestivalMessageService $festivalMessageService;

    /**
     * Constructor with service injection
     *
     * @param FestivalMessageService $festivalMessageService
     */
    public function __construct(FestivalMessageService $festivalMessageService)
    {
        $this->festivalMessageService = $festivalMessageService;
    }

    /**
     * Show the festival messages page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $messages = $this->festivalMessageService->getCampaignsPaginated(perPage: 10);
        $totalCustomers = Customer::count();

        return view('festival_messages.index', compact('messages', 'totalCustomers'));
    }

    /**
     * Send festival messages
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(Request $request)
    {
        try {
            $sendTo = $request->input('send_to');

            // Conditional validation based on send_to
            $rules = [
                'send_to' => 'required|in:all,selected',
                'message' => 'required|string|max:1000',
                'sent_date' => 'required|date',
            ];

            // Only validate dates if send_to is 'selected'
            if ($sendTo === 'selected') {
                $rules['from_date'] = 'required|date';
                $rules['to_date'] = 'required|date';
            }

            $validated = $request->validate($rules);

            // Extract customer IDs from date range if needed
            $customerIds = [];
            if ($sendTo === 'selected' && isset($validated['from_date']) && isset($validated['to_date'])) {
                $customerIds = Customer::whereBetween('created_at', [
                    $validated['from_date'],
                    $validated['to_date'],
                ])->pluck('id')->toArray();
            }

            // Delegate to service
            $result = $this->festivalMessageService->sendFestivalMessages(
                message: $validated['message'],
                sendTo: $validated['send_to'],
                customerIds: $customerIds,
                campaignName: null
            );

            return response()->json($result, 200);

        } catch (\InvalidArgumentException $e) {
            // Validation error
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Form validation error
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            // Unexpected error
            \Illuminate\Support\Facades\Log::error('Festival Message Send Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while sending messages: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get customer count by date range (AJAX)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCountByDateRange(Request $request)
    {
        try {
            $validated = $request->validate([
                'from_date' => 'required|date',
                'to_date' => 'required|date',
            ]);

            $count = $this->festivalMessageService->getCustomerCountByDateRange(
                $validated['from_date'],
                $validated['to_date']
            );

            return response()->json(['count' => $count], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching count',
                'count' => 0
            ], 422);
        }
    }
}
