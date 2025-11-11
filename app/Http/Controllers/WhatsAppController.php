<?php

namespace App\Http\Controllers;

use App\Models\WhatsAppNotification;
use Illuminate\Http\Request;

class WhatsAppController extends Controller
{
    // Show service notification page
    public function service()
    {
        $notifications = WhatsAppNotification::latest()->get();
        return view('whatsapp.service', compact('notifications'));
    }

    // Show festival notification page
    public function festival()
    {
        return view('whatsapp.festival');
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
}
