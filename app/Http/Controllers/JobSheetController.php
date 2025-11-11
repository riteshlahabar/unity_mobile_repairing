<?php

namespace App\Http\Controllers;

use App\Models\JobSheet;
use App\Models\Customer;
use App\Models\DevicePhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\WhatsAppService;  // ✅ ADD THIS
use App\Services\JobSheetPdfService;  // ✅ ADD THIS IF MISSING
use App\Http\Controllers\SendMessageController;  // ✅ ADD THIS IF MISSING



class JobSheetController extends Controller
{
    // Show all jobsheets with search
    public function index(Request $request)
{
    $search = $request->input('search');

    $query = JobSheet::with('customer');

    // Search functionality - FIXED
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('jobsheet_id', 'LIKE', "%{$search}%")
              ->orWhere('company', 'LIKE', "%{$search}%")
              ->orWhere('model', 'LIKE', "%{$search}%")
              ->orWhere('color', 'LIKE', "%{$search}%")
              ->orWhere('series', 'LIKE', "%{$search}%")
              ->orWhere('problem_description', 'LIKE', "%{$search}%")
              ->orWhere('imei', 'LIKE', "%{$search}%")
              ->orWhere('technician', 'LIKE', "%{$search}%")
              ->orWhereHas('customer', function($subQ) use ($search) {
                  $subQ->where('full_name', 'LIKE', "%{$search}%")
                       ->orWhere('customer_id', 'LIKE', "%{$search}%")
                       ->orWhere('contact_no', 'LIKE', "%{$search}%")
                       ->orWhere('whatsapp_no', 'LIKE', "%{$search}%");
              });
        });
    }

    $jobSheets = $query->latest()->paginate(20);

    return view('jobsheets.index', compact('jobSheets', 'search'));
}

    // Show create form
    public function create()
    {
        return view('jobsheets.create');
    }

    // Store new jobsheet
    public function store(Request $request)
{
    try {
        // Log the incoming request for debugging
        \Log::info('JobSheet Store Request:', $request->all());

        // Validate request
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,customer_id',
            'company' => 'required|string',
            'model' => 'required|string',
            'color' => 'required|string',
            'series' => 'required|string',
            'imei' => 'nullable|string|max:15',
            'problem_description' => 'required|string',
            'device_condition' => 'required|in:fresh,shop_return,other',
            'water_damage' => 'required|in:none,lite,full',
            'physical_damage' => 'required|in:none,lite,full',
            'estimated_cost' => 'required|numeric|min:0',
            'advance' => 'nullable|numeric|min:0',
            'device_photos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Generate SERIAL JobSheet ID
        $validated['jobsheet_id'] = JobSheet::generateJobSheetId();
        
        // Calculate balance
        $validated['balance'] = $validated['estimated_cost'] - ($validated['advance'] ?? 0);
        
        // Set default status
        $validated['status'] = 'in_progress';

        // Handle checkboxes
        $validated['status_dead'] = $request->has('status_dead') ? true : false;
        $validated['status_damage'] = $request->has('status_damage') ? true : false;
        $validated['status_on'] = $request->has('status_on') ? true : false;
        $validated['accessory_sim_tray'] = $request->has('accessory_sim_tray') ? true : false;
        $validated['accessory_sim_card'] = $request->has('accessory_sim_card') ? true : false;
        $validated['accessory_memory_card'] = $request->has('accessory_memory_card') ? true : false;
        $validated['accessory_mobile_cover'] = $request->has('accessory_mobile_cover') ? true : false;
        $validated['jobsheet_required'] = $request->has('jobsheet_required') ? true : false;

        // Optional fields
        $validated['other_accessories'] = $request->input('other_accessories');
        $validated['device_password'] = $request->input('device_password');
        $validated['pattern_image'] = $request->input('pattern_image');
        $validated['technician'] = $request->input('technician');
        $validated['location'] = $request->input('location');
        $validated['delivered_date'] = $request->input('delivered_date');
        $validated['delivered_time'] = $request->input('delivered_time');
        $validated['remarks'] = $request->input('remarks');
        $validated['terms_conditions'] = $request->input('terms_conditions');

        // Create JobSheet
        $jobSheet = JobSheet::create($validated);

        // Log successful creation
        \Log::info('JobSheet Created Successfully:', ['jobsheet_id' => $jobSheet->jobsheet_id]);

        // Handle device photos
        if ($request->hasFile('device_photos')) {
            foreach ($request->file('device_photos') as $photo) {
                $path = $photo->store('device_photos', 'public');
                
                DevicePhoto::create([
                    'job_sheet_id' => $jobSheet->id,
                    'photo_path' => $path,
                ]);
            }
        }

        // ✅ Send Device Received Message with PDF
        $messageController = new SendMessageController(new \App\Services\WhatsAppService());
        $messageController->sendDeviceReceived($jobSheet);

        // ALWAYS return JSON for AJAX requests
        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'JobSheet created successfully!',
                'jobsheet_id' => $jobSheet->jobsheet_id,
                'jobsheet' => $jobSheet->load('customer')
            ], 201);
        }

        return redirect()->route('jobsheets.index')->with('success', 'JobSheet created successfully!');

    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('JobSheet Validation Error:', $e->errors());
        
        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        return back()->withErrors($e->errors())->withInput();

    } catch (\Exception $e) {
        \Log::error('JobSheet Creation Error:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }

        return back()->with('error', 'An error occurred')->withInput();
    }
}


    // Show single jobsheet
    public function show($id)
{
    $jobSheet = JobSheet::with(['customer', 'devicePhotos'])
        ->where('jobsheet_id', $id)
        ->firstOrFail();
    
    return view('jobsheets.show', compact('jobSheet'));
}

    // Show edit form
public function edit($id)
{
    $jobSheet = JobSheet::with('customer')
        ->where('jobsheet_id', $id)
        ->firstOrFail();
    
    return view('jobsheets.edit', compact('jobSheet'));
}


   
// Update jobsheet
public function update(Request $request, $id)
{
    try {
        $jobSheet = JobSheet::where('jobsheet_id', $id)->firstOrFail();

        $validated = $request->validate([
            'company' => 'required|string',
            'model' => 'required|string',
            'color' => 'required|string',
            'series' => 'required|string',
            'imei' => 'nullable|string|max:15',
            'problem_description' => 'required|string',
            'device_condition' => 'required|in:fresh,shop_return,other',
            'water_damage' => 'required|in:none,lite,full',
            'physical_damage' => 'required|in:none,lite,full',
            'estimated_cost' => 'required|numeric|min:0',
            'advance' => 'required|numeric|min:0',
        ]);

        // Calculate balance
        $validated['balance'] = $validated['estimated_cost'] - $validated['advance'];

        // Handle checkboxes
        $validated['status_dead'] = $request->has('status_dead');
        $validated['status_damage'] = $request->has('status_damage');
        $validated['status_on'] = $request->has('status_on');
        $validated['accessory_sim_tray'] = $request->has('accessory_sim_tray');
        $validated['accessory_sim_card'] = $request->has('accessory_sim_card');
        $validated['accessory_memory_card'] = $request->has('accessory_memory_card');
        $validated['accessory_mobile_cover'] = $request->has('accessory_mobile_cover');
        $validated['jobsheet_required'] = $request->has('jobsheet_required');

        // Optional fields
        $validated['other_accessories'] = $request->input('other_accessories');
        $validated['device_password'] = $request->input('device_password');
        $validated['technician'] = $request->input('technician');
        $validated['location'] = $request->input('location');
        $validated['remarks'] = $request->input('remarks');
        $validated['terms_conditions'] = $request->input('terms_conditions');

        // Update jobsheet
        $jobSheet->update($validated);

        return redirect()
            ->route('jobsheets.edit', $jobSheet->jobsheet_id)
            ->with('success', 'JobSheet updated successfully!');

    } catch (\Exception $e) {
        \Log::error('JobSheet Update Error: ' . $e->getMessage());
        return back()
            ->with('error', 'An error occurred while updating')
            ->withInput();
    }
}

    
    // Mark as complete (AJAX)
public function markComplete($id)
{
    try {
        $jobSheet = JobSheet::where('jobsheet_id', $id)->firstOrFail();
        $jobSheet->update(['status' => 'completed']);

        // Send Repair Completed Message
        $messageController = new SendMessageController(new \App\Services\WhatsAppService());
        $messageController->sendRepairCompleted($jobSheet);

        return response()->json([
            'success' => true,
            'message' => 'JobSheet marked as complete',
            'jobsheet' => [
                'jobsheet_id' => $jobSheet->jobsheet_id,
                'status' => $jobSheet->status,
                'balance' => $jobSheet->balance
            ]
        ]);

    } catch (\Exception $e) {
        \Log::error('Mark Complete Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

public function generateDeliveryOTP($id)
{
    try {
        $jobSheet = JobSheet::where('jobsheet_id', $id)->firstOrFail();

        // ✅ Send OTP via Message Controller
        $messageController = new SendMessageController(new \App\Services\WhatsAppService());
        $result = $messageController->sendDeliveryOTP($jobSheet);

        return response()->json($result);

    } catch (\Exception $e) {
        \Log::error('OTP Generation Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}

// Verify OTP and mark as delivered
public function verifyOTPAndDeliver(Request $request, $id)
{
    try {
        $jobSheet = JobSheet::where('jobsheet_id', $id)->firstOrFail();

        $validated = $request->validate([
            'otp' => 'required|string|size:6'
        ]);

        // ✅ Verify OTP via Message Controller
        $messageController = new SendMessageController(new \App\Services\WhatsAppService());
        $verification = $messageController->verifyDeliveryOTP($jobSheet, $validated['otp']);

        if (!$verification['success']) {
            return response()->json($verification, 400);
        }

        // Mark jobsheet as delivered
        $jobSheet->update(['status' => 'delivered']);

        // ✅ Send Thank You Message
        $messageController->sendThankYou($jobSheet);

        return response()->json([
            'success' => true,
            'message' => 'JobSheet marked as delivered and customer thanked!'
        ]);

    } catch (\Exception $e) {
        \Log::error('OTP Verification Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}

    // Mark as delivered
    // Mark as delivered (AJAX with OTP)
// Mark as delivered (only check if OTP is verified, don't verify again)
public function markDelivered(Request $request, $id)
{
    try {
        $jobSheet = JobSheet::where('jobsheet_id', $id)->firstOrFail();

        // Check if OTP is already verified
        $messageController = new SendMessageController(new WhatsAppService());
        
        if (!$messageController->isOTPVerified($jobSheet)) {
            return response()->json([
                'success' => false,
                'message' => 'Please verify OTP first'
            ], 400);
        }

        // Mark jobsheet as delivered
        $jobSheet->update(['status' => 'delivered']);

        // Clear OTP from cache
        $messageController->clearOTP($jobSheet);

        // Send Thank You Message
        $messageController->sendThankYou($jobSheet);

        return response()->json([
            'success' => true,
            'message' => 'JobSheet marked as delivered',
            'jobsheet' => [
                'jobsheet_id' => $jobSheet->jobsheet_id,
                'status' => $jobSheet->status,
                'balance' => $jobSheet->balance
            ]
        ]);

    } catch (\Exception $e) {
        \Log::error('Mark Delivered Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
// Delete jobsheet
public function destroy($id)
{
    try {
        $jobSheet = JobSheet::where('jobsheet_id', $id)->firstOrFail();
        
        // Delete associated device photos
        foreach ($jobSheet->devicePhotos as $photo) {
            // Delete file from storage
            if (Storage::disk('public')->exists($photo->photo_path)) {
                Storage::disk('public')->delete($photo->photo_path);
            }
            // Delete database record
            $photo->delete();
        }
        
        // Delete jobsheet
        $jobSheet->delete();
        
        return redirect()
            ->route('jobsheets.index')
            ->with('success', 'JobSheet deleted successfully!');
        
    } catch (\Exception $e) {
        \Log::error('JobSheet Delete Error: ' . $e->getMessage());
        return back()->with('error', 'Failed to delete jobsheet');
    }
}

// Download JobSheet PDF
public function downloadPDF($id)
{
    try {
        $jobSheet = JobSheet::with(['customer', 'devicePhotos'])
            ->where('jobsheet_id', $id)
            ->firstOrFail();
        
        // Generate PDF
        $pdf = Pdf::loadView('pdf.jobsheet', compact('jobSheet'));
        
        // Download with filename
        $fileName = 'JobSheet_' . $jobSheet->jobsheet_id . '.pdf';
        
        return $pdf->download($fileName);
        
    } catch (\Exception $e) {
        \Log::error('PDF Download Error: ' . $e->getMessage());
        return back()->with('error', 'Failed to generate PDF');
    }
}
}
