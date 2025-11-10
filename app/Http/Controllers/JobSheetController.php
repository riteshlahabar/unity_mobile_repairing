<?php

namespace App\Http\Controllers;

use App\Models\JobSheet;
use App\Models\Customer;
use App\Models\DevicePhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

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
        $jobSheet = JobSheet::where('jobsheet_id', $id)->with(['customer', 'devicePhotos'])->firstOrFail();
        return view('jobsheets.show', compact('jobSheet'));
    }

    // Show edit form
    public function edit($id)
    {
        $jobSheet = JobSheet::where('jobsheet_id', $id)->with('devicePhotos')->firstOrFail();
        return view('jobsheets.edit', compact('jobSheet'));
    }

    // Update jobsheet
    public function update(Request $request, $id)
    {
        $jobSheet = JobSheet::where('jobsheet_id', $id)->firstOrFail();

        $validated = $request->validate([
            'company' => 'required|string',
            'model' => 'required|string',
            'problem_description' => 'required|string',
            'estimated_cost' => 'required|numeric|min:0',
            'advance' => 'nullable|numeric|min:0',
        ]);

        $validated['balance'] = $validated['estimated_cost'] - ($validated['advance'] ?? 0);

        $jobSheet->update($validated);

        return redirect()->route('jobsheets.index')->with('success', 'JobSheet updated successfully!');
    }

    // Delete jobsheet
    public function destroy($id)
    {
        $jobSheet = JobSheet::where('jobsheet_id', $id)->firstOrFail();
        
        foreach ($jobSheet->devicePhotos as $photo) {
            Storage::disk('public')->delete($photo->photo_path);
            $photo->delete();
        }

        $jobSheet->delete();

        return redirect()->route('jobsheets.index')->with('success', 'JobSheet deleted successfully!');
    }

    // Mark as complete
    public function markComplete($id)
    {
        $jobSheet = JobSheet::where('jobsheet_id', $id)->firstOrFail();
        $jobSheet->update(['status' => 'completed']);

        return redirect()->route('jobsheets.index')->with('success', 'JobSheet marked as complete!');
    }

    // Mark as delivered
    public function markDelivered($id)
    {
        $jobSheet = JobSheet::where('jobsheet_id', $id)->firstOrFail();
        $jobSheet->update(['status' => 'delivered']);

        return redirect()->route('jobsheets.index')->with('success', 'JobSheet marked as delivered!');
    }
}
