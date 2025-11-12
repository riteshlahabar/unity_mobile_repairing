<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\FestivalMessage;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FestivalMessageController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    // Show festival message page
    public function index()
    {
        $totalCustomers = Customer::count();
        $sentMessages = FestivalMessage::where('status', 'sent')->count();
        $failedMessages = FestivalMessage::where('status', 'failed')->count();
        
        $messages = FestivalMessage::with('customer')
            ->latest()
            ->paginate(20);
        
        return view('festival.index', compact('totalCustomers', 'sentMessages', 'failedMessages', 'messages'));
    }

    // Send festival messages
    public function sendMessages(Request $request)
    {
        try {
            $validated = $request->validate([
                'message' => 'required|string|max:1000',
                'send_to' => 'required|in:all,selected',
                'customer_ids' => 'required_if:send_to,selected|array',
            ]);

            $message = $validated['message'];
            
            // Get customers
            if ($validated['send_to'] == 'all') {
                $customers = Customer::all();
            } else {
                $customers = Customer::whereIn('id', $validated['customer_ids'])->get();
            }

            $successCount = 0;
            $failCount = 0;

            foreach ($customers as $customer) {
                // Send message via WhatsApp
                $result = $this->whatsappService->sendMessage($customer->whatsapp_no, $message);

                // Save to database
                $festivalMessage = FestivalMessage::create([
                    'customer_id' => $customer->id,
                    'message' => $message,
                    'status' => $result['success'] ? 'sent' : 'failed',
                    'response' => json_encode($result),
                ]);

                if ($result['success']) {
                    $successCount++;
                } else {
                    $failCount++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Messages sent successfully! Success: {$successCount}, Failed: {$failCount}",
                'success_count' => $successCount,
                'fail_count' => $failCount,
            ]);

        } catch (\Exception $e) {
            Log::error('Festival Message Send Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // Get customers for selection
    public function getCustomers()
    {
        $customers = Customer::select('id', 'customer_id', 'full_name', 'contact_no', 'whatsapp_no')
            ->orderBy('full_name')
            ->get();
        
        return response()->json($customers);
    }
}
