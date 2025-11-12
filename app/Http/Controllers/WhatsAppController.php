<?php

namespace App\Http\Controllers;

use App\Models\WhatsAppNotification;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\FestivalMessage;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Log;

class WhatsAppController extends Controller
{
    // Show service notification page
    public function service()
    {
        $notifications = WhatsAppNotification::latest()->get();
        return view('whatsapp.service', compact('notifications'));
    }

    public function festival()
{
    $totalCustomers = \App\Models\Customer::count();
    $sentMessages = \App\Models\FestivalMessage::where('status', 'sent')->count();
    $failedMessages = \App\Models\FestivalMessage::where('status', 'failed')->count();
    
    $messages = \App\Models\FestivalMessage::with('customer')
        ->latest()
        ->paginate(20);
    
    return view('whatsapp.festival', compact('totalCustomers', 'sentMessages', 'failedMessages', 'messages'));
}


    // Store notification
    public function storeNotification(Request $request)
    {
        try {
            $validated = $request->validate([
                'notification_title' => 'required|string|max:255',
                'notification_message' => 'required|string',
            ]);

            $notification = WhatsAppNotification::create([
                'title' => $validated['notification_title'],
                'message' => $validated['notification_message'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Notification saved successfully!',
                'notification' => $notification
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Notification save error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // Update notification
    public function updateNotification(Request $request, $id)
    {
        try {
            $notification = WhatsAppNotification::findOrFail($id);

            $validated = $request->validate([
                'notification_title' => 'required|string|max:255',
                'notification_message' => 'required|string',
            ]);

            $notification->update([
                'title' => $validated['notification_title'],
                'message' => $validated['notification_message'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Notification updated successfully!',
                'notification' => $notification
            ]);

        } catch (\Exception $e) {
            \Log::error('Notification update error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // Delete notification
    public function deleteNotification($id)
    {
        try {
            $notification = WhatsAppNotification::findOrFail($id);
            $notification->delete();

            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // Send WhatsApp message
    public function sendMessage(Request $request)
    {
        // Implement WhatsApp API integration here
        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully!'
        ]);
    }

    public function sendFestivalMessages(Request $request)
{
    try {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
            'send_to' => 'required|in:all,selected',
            'from_date' => 'required_if:send_to,selected|date',
            'to_date' => 'required_if:send_to,selected|date',
        ]);

        $message = $validated['message'];
        $whatsappService = new WhatsAppService();
        
        // Get customers
        if ($validated['send_to'] == 'all') {
            $customers = Customer::all();
        } else {
            $customers = Customer::whereDate('created_at', '>=', $validated['from_date'])
                ->whereDate('created_at', '<=', $validated['to_date'])
                ->get();
        }

        $successCount = 0;
        $failCount = 0;

        foreach ($customers as $customer) {
            $result = $whatsappService->sendMessage($customer->whatsapp_no, $message);

            FestivalMessage::create([
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
public function getFestivalCustomers()
{
    $customers = Customer::select('id', 'customer_id', 'full_name', 'contact_no', 'whatsapp_no')
        ->orderBy('full_name')
        ->get();
    
    return response()->json($customers);
}

// Get customer count by date range
public function getCustomerCountByDate(Request $request)
{
    $fromDate = $request->from_date;
    $toDate = $request->to_date;
    
    $count = Customer::whereDate('created_at', '>=', $fromDate)
        ->whereDate('created_at', '<=', $toDate)
        ->count();
    
    return response()->json(['count' => $count]);
}
}
