<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WhatsAppController extends Controller
{
    // Festival messages
    public function festival()
    {
        return view('whatsapp.festival');
    }

    // Service notifications
    public function service()
    {
        return view('whatsapp.service');
    }

    // Send message
    public function sendMessage(Request $request)
    {
        // Add your WhatsApp API logic here
        return redirect()->back()->with('success', 'Message sent successfully!');
    }
}
