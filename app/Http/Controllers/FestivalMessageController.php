<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Services\FestivalMessageService;
use App\Models\Customer;
=======
use App\Repositories\Contracts\FestivalMessageRepositoryInterface;
use App\Services\FestivalMessageService;
>>>>>>> 0963cebdc0528a837022693382951a181cdac698
use Illuminate\Http\Request;

/**
<<<<<<< HEAD
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
=======
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
>>>>>>> 0963cebdc0528a837022693382951a181cdac698
     * @return \Illuminate\View\View
     */
    public function index()
    {
<<<<<<< HEAD
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
=======
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
>>>>>>> 0963cebdc0528a837022693382951a181cdac698
    {
        try {
            $sendTo = $request->input('send_to');

            // Conditional validation based on send_to
            $rules = [
                'send_to' => 'required|in:all,selected',
                'message' => 'required|string|max:1000',
                'sent_date' => 'required|date',
            ];

<<<<<<< HEAD
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
=======
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
            
>>>>>>> 0963cebdc0528a837022693382951a181cdac698
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
<<<<<<< HEAD
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
=======
     * Get customers for selection (AJAX)
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomers()
    {
        $customers = $this->service->getCustomersForSelection();
        return response()->json($customers);
>>>>>>> 0963cebdc0528a837022693382951a181cdac698
    }
}
