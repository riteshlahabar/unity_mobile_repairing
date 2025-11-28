<?php

namespace App\Http\Controllers;

use App\Models\JobSheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repositories\Contracts\JobSheetRepositoryInterface;
use App\Services\JobSheetService;
use App\Services\Contracts\PdfServiceInterface;
use App\Services\Contracts\NotificationServiceInterface;

class JobSheetController extends Controller
{
    public function __construct(
        protected JobSheetRepositoryInterface $repository,
        protected JobSheetService $service,
        protected PdfServiceInterface $pdfService,
        protected NotificationServiceInterface $notification
    ) {}

    public function index(Request $request)
    {
        $search = $request->input('search');

        $jobSheets = $search 
            ? $this->repository->search($search, 20)
            : $this->repository->paginate(20);

        return view('jobsheets.index', compact('jobSheets', 'search'));
    }

    public function create()
    {
        $generalInfo = DB::table('general_info')->first();

        return view('jobsheets.create', [
            'termsConditions' => $generalInfo->terms_conditions ?? '',
            'remarks' => $generalInfo->remarks ?? '',
        ]);
    }

    public function store(Request $request)
    {
        try {
            Log::info('JobSheet Store Request:', $request->all());

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
                'notes' => 'nullable|string',
            ]);

            // Handle checkboxes
            $validated['status_dead'] = $request->has('status_dead');
            $validated['status_damage'] = $request->has('status_damage');
            $validated['status_on'] = $request->has('status_on');
            $validated['accessory_sim_tray'] = $request->has('accessory_sim_tray');
            $validated['accessory_sim_card'] = $request->has('accessory_sim_card');
            $validated['accessory_memory_card'] = $request->has('accessory_memory_card');
            $validated['accessory_mobile_cover'] = $request->has('accessory_mobile_cover');

            // Optional fields
            $validated['other_accessories'] = $request->input('other_accessories');
            $validated['device_password'] = $request->input('device_password');
            $validated['pattern_image'] = $request->input('pattern_image');
            $validated['technician'] = $request->input('technician');
            $validated['location'] = $request->input('location');
            $validated['delivered_date'] = $request->input('delivered_date');
            $validated['delivered_time'] = $request->input('delivered_time');
            $validated['notes'] = $request->input('notes', null);

            $files = $request->hasFile('device_photos') ? $request->file('device_photos') : [];
            
            $result = $this->service->createJobSheet($validated, $files);

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json($result, 201);
            }

            return redirect()->route('jobsheets.index')->with('success', $result['message']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('JobSheet Validation Error:', $e->errors());
            
            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }

            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            Log::error('JobSheet Creation Error:', [
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

    public function show($id)
    {
        $jobSheet = $this->repository->findByJobSheetId($id);
        $generalInfo = DB::table('general_info')->first();
        
        return view('jobsheets.show', compact('jobSheet', 'generalInfo'));
    }

    public function edit($id)
    {
        $jobSheet = $this->repository->findByJobSheetId($id);
        return view('jobsheets.edit', compact('jobSheet'));
    }

    public function update(Request $request, $id)
    {
        try {
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
                'notes' => 'nullable|string',
            ]);

            // Handle checkboxes
            $validated['status_dead'] = $request->has('status_dead');
            $validated['status_damage'] = $request->has('status_damage');
            $validated['status_on'] = $request->has('status_on');
            $validated['accessory_sim_tray'] = $request->has('accessory_sim_tray');
            $validated['accessory_sim_card'] = $request->has('accessory_sim_card');
            $validated['accessory_memory_card'] = $request->has('accessory_memory_card');
            $validated['accessory_mobile_cover'] = $request->has('accessory_mobile_cover');

            // Optional fields
            $validated['other_accessories'] = $request->input('other_accessories');
            $validated['device_password'] = $request->input('device_password');
            $validated['technician'] = $request->input('technician');
            $validated['location'] = $request->input('location');
            $validated['notes'] = $request->input('notes', null);

            $result = $this->service->updateJobSheet($id, $validated);

            return redirect()
                ->route('jobsheets.edit', $id)
                ->with('success', $result['message']);

        } catch (\Exception $e) {
            Log::error('JobSheet Update Error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while updating')->withInput();
        }
    }

    public function markComplete($id)
    {
        try {
            $result = $this->service->markComplete($id);
            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('Mark Complete Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function generateDeliveryOTP($id)
    {
        try {
            $jobSheet = $this->repository->findByJobSheetId($id);
            $result = $this->notification->sendDeliveryOTP($jobSheet);
            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('OTP Generation Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verifyOTPAndDeliver(Request $request, $id)
{
    try {
        $jobSheet = $this->repository->findByJobSheetId($id);

        $validated = $request->validate([
            'otp' => 'required|string|size:6'
        ]);

        $verification = $this->notification->verifyDeliveryOTP($jobSheet, $validated['otp']);

        if (!$verification['success']) {
            return response()->json($verification, 400);
        }

        // ✅ DO NOT MARK delivered here or CLEAR OTP yet
        // Let frontend call markDelivered separately

        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully'
        ]);

    } catch (\Exception $e) {
        Log::error('OTP Verification Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}

public function markDelivered(Request $request, $id)
{
    try {
        $jobsheet = $this->repository->findByJobSheetId($id);

        if (!$this->notification->isOTPVerified($id)) {
            return response()->json([
                'success' => false,
                'message' => 'Please verify OTP first'
            ], 400);
        }

        $result = $this->service->markDelivered($id);
        
        // ✅ CLEAR OTP after delivery only
        $this->notification->clearOTP($jobsheet);

        return response()->json($result);

    } catch (\Exception $e) {
        Log::error('Mark Delivered Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}



    public function destroy($id)
    {
        try {
            $this->service->deleteJobSheet($id);
            return redirect()->route('jobsheets.index')->with('success', 'JobSheet deleted successfully!');
            
        } catch (\Exception $e) {
            Log::error('JobSheet Delete Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete jobsheet');
        }
    }

    public function downloadPDF($id)
    {
        try {
            $jobSheet = $this->repository->findByJobSheetId($id);
            $fileName = 'JobSheet_' . $jobSheet->jobsheet_id . '.pdf';
            
            return $this->pdfService->download($jobSheet, $fileName);
            
        } catch (\Exception $e) {
            Log::error('PDF Download Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to generate PDF');
        }
    }

    public function printPDF($id)
    {
        try {
            $jobSheet = $this->repository->findByJobSheetId($id);
            $fileName = 'JobSheet_' . $jobSheet->jobsheet_id . '.pdf';
            
            return $this->pdfService->stream($jobSheet, $fileName);

        } catch (\Exception $e) {
            Log::error('PDF Print Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to generate PDF for printing');
        }
    }

    public function saveWarranty(Request $request, $id)
{
    $request->validate([
        'warranty_months' => 'nullable|integer|min:1|max:12',  // ✅ Changed to nullable
        'previous_jobsheet_id' => 'nullable|string|max:50'
    ]);
    
    $jobsheet = JobSheet::where('jobsheet_id', $id)->firstOrFail();
    
    // ✅ Handle both cases
    if ($request->warranty_months) {
        $jobsheet->warranty = (int) $request->warranty_months;
        $jobsheet->warranty_start_date = now()->format('Y-m-d');
    }
    
    $jobsheet->previous_jobsheet_id = $request->previous_jobsheet_id ?: null;
    $jobsheet->save();
    
    $message = $request->warranty_months 
        ? "{$request->warranty_months} months warranty saved"
        : "Previous jobsheet warranty linked";
    
    return response()->json([
        'success' => true,
        'message' => $message
    ]);
}


public function getPreviousWarranties($id)
{
    try {
        Log::info('getPreviousWarranties called', ['id' => $id]);
        
        $currentJobsheet = JobSheet::where('jobsheet_id', $id)->firstOrFail();
        Log::info('Current jobsheet found', ['customer_id' => $currentJobsheet->customer_id]);
        
        $customerId = $currentJobsheet->customer_id;
        
        $previousJobsheets = JobSheet::where('customer_id', $customerId)
            ->where('jobsheet_id', '!=', $id)
            ->whereNotNull('warranty')
            ->whereNotNull('warranty_start_date')
            ->get();
            
        Log::info('Raw previous jobsheets', ['count' => $previousJobsheets->count(), 'data' => $previousJobsheets->toArray()]);
        
        $filtered = $previousJobsheets->filter(function ($job) {
            $warrantyMonths = (int) $job->warranty;
            
            if ($warrantyMonths <= 0 || !$job->warranty_start_date) {
                return false;
            }
            
            $startDate = \Carbon\Carbon::parse($job->warranty_start_date);
            $expiryDate = $startDate->copy()->addMonths($warrantyMonths);
            
            return $expiryDate->isFuture();
        });
        
        Log::info('Filtered jobsheets', ['count' => $filtered->count()]);
        
        $mapped = $filtered->map(function ($job) {
            $warrantyMonths = (int) $job->warranty;
            $startDate = \Carbon\Carbon::parse($job->warranty_start_date);
            $expiryDate = $startDate->copy()->addMonths($warrantyMonths);
            
            return [
                'jobsheet_id' => $job->jobsheet_id,
                'warranty' => $warrantyMonths,
                'warranty_start_date' => $job->warranty_start_date,
                'warranty_expiry_date' => $expiryDate->format('Y-m-d'),
                'days_remaining' => $expiryDate->diffInDays(now()),
                'previous_jobsheet_id' => $job->previous_jobsheet_id
            ];
        });
        
        return response()->json([
            'success' => true,
            'previous_jobsheets' => $mapped->values()->take(5)
        ]);
        
    } catch (\Exception $e) {
        Log::error('Previous Warranties ERROR: ' . $e->getMessage(), [
            'id' => $id,
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
            'previous_jobsheets' => []
        ], 500);
    }
}


    public function printLabel($id)
    {
        $jobsheet = $this->repository->findByJobSheetId($id);
        return view('print.label', compact('jobsheet'));
    }
    
   public function updateStatus(Request $request, $jobsheetId)
{
    $request->validate([
        'status' => 'required|string'
    ]);

    $allowedStatuses = [
        'in_progress',
        'call_info',
        'approval_pending',
        'customer_approved',
        'not_okay_return',
        'ready',
        'return',
        'delivered'
    ];

    $newStatus = $request->input('status');

    if (!in_array($newStatus, $allowedStatuses)) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid status'
        ], 422);
    }

    $jobSheet = JobSheet::where('jobsheet_id', $jobsheetId)->firstOrFail();
    $jobSheet->status = $newStatus;
    $jobSheet->save();

    // Send WhatsApp on status update
    app(\App\Services\Contracts\NotificationServiceInterface::class)
        ->sendStatusWhatsAppMessage($jobSheet, $newStatus);

    return response()->json([
        'success' => true,
        'message' => 'Status updated successfully'
    ]);
}



protected function sendWhatsAppMessageForStatus(JobSheet $jobSheet, string $status)
{
    // Compose message based on status
   $allowedTransitions = [
    'in_progress' => ['pending_for_spare_parts', 'not_ok_returned'],
    'pending_for_spare_parts' => ['waiting_for_approval'],
    'waiting_for_approval' => ['customer_approved'],
    'customer_approved' => ['mark_completed'],
    'mark_completed' => ['completed'],
    'not_ok_returned' => ['returned'],
];

$currentStatus = $jobSheet->status;
$newStatus = $request->input('status');

if (!isset($allowedTransitions[$currentStatus]) || !in_array($newStatus, $allowedTransitions[$currentStatus])) {
    return response()->json([
        'success' => false,
        'message' => 'Invalid status transition'
    ], 422);
}

$jobSheet->status = $newStatus;
$jobSheet->save();

// Send WhatsApp message for new status
app(NotificationServiceInterface::class)->sendStatusWhatsAppMessage($jobSheet, $newStatus);

return response()->json(['success' => true, 'message' => 'Status updated']);

}

}
