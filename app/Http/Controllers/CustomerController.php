<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\JobSheet;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CustomerController extends Controller
{
    // Show all customers (only today's delivered customers + customers without delivered jobsheets)
    public function index(Request $request)
{
    $search = $request->input('search');
    $today = Carbon::today();

    $query = Customer::with(['jobSheets' => function($q) {
        $q->latest();
    }]);

    // Search functionality - FIXED
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('customer_id', 'LIKE', "%{$search}%")
              ->orWhere('full_name', 'LIKE', "%{$search}%")
              ->orWhere('contact_no', 'LIKE', "%{$search}%")
              ->orWhere('whatsapp_no', 'LIKE', "%{$search}%")
              ->orWhere('address', 'LIKE', "%{$search}%");
        });
    }

    // Filter: Show customers who have jobsheets delivered today OR have no delivered jobsheets
    $query->where(function($q) use ($today) {
        // Customers with jobsheets delivered today
        $q->whereHas('jobSheets', function($subQ) use ($today) {
            $subQ->where('status', 'delivered')
                 ->whereDate('updated_at', $today);
        })
        // OR customers without any delivered jobsheets
        ->orWhereDoesntHave('jobSheets', function($subQ) {
            $subQ->where('status', 'delivered');
        })
        // OR customers with no jobsheets at all
        ->orWhereDoesntHave('jobSheets');
    });

    $customers = $query->latest()->paginate(20)->appends(['search' => $search]);

    return view('customers.index', compact('customers', 'search'));
}


    // Show create form
    public function create()
    {
        return view('customers.create');
    }

    // Check if customer exists by contact number (AJAX)
    public function checkContact(Request $request)
    {
        $contactNo = $request->input('contact_no');
        
        $customer = Customer::where('contact_no', $contactNo)->first();
        
        if ($customer) {
            return response()->json([
                'exists' => true,
                'customer' => $customer
            ]);
        }
        
        return response()->json([
            'exists' => false
        ]);
    }

    // Store new customer
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'full_name' => 'required|string|max:255',
                'address' => 'required|string',
                'contact_no' => 'required|digits:10|regex:/^[6-9][0-9]{9}$/|unique:customers,contact_no',
                'alternate_no' => 'nullable|digits:10|regex:/^[6-9][0-9]{9}$/',
                'whatsapp_no' => 'required|digits:10|regex:/^[6-9][0-9]{9}$/',
            ]);

            $validated['customer_id'] = Customer::generateCustomerId();
            $customer = Customer::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Customer created successfully!',
                'customer_id' => $customer->customer_id,
                'customer' => $customer
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            \Log::error('Customer creation error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    // Show single customer
    public function show($id)
    {
        $customer = Customer::where('customer_id', $id)->firstOrFail();
        $jobSheets = $customer->jobSheets()->latest()->paginate(10);
        return view('customers.show', compact('customer', 'jobSheets'));
    }

    // Show edit form
    public function edit($id)
    {
        $customer = Customer::where('customer_id', $id)->firstOrFail();
        return view('customers.edit', compact('customer'));
    }

    // Update customer
    public function update(Request $request, $id)
    {
        $customer = Customer::where('customer_id', $id)->firstOrFail();

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'address' => 'required|string',
            'contact_no' => 'required|digits:10|regex:/^[6-9][0-9]{9}$/|unique:customers,contact_no,' . $customer->id,
            'alternate_no' => 'nullable|digits:10|regex:/^[6-9][0-9]{9}$/',
            'whatsapp_no' => 'required|digits:10|regex:/^[6-9][0-9]{9}$/',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully!');
    }

    // Delete customer
    public function destroy($id)
    {
        $customer = Customer::where('customer_id', $id)->firstOrFail();
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully!');
    }

    // Search customers (AJAX)
   // Search customers (AJAX)
public function search(Request $request)
{
    $query = $request->input('query');

    if (!$query) {
        return response()->json([]);
    }

    $customers = Customer::where('full_name', 'LIKE', "%{$query}%")
        ->orWhere('customer_id', 'LIKE', "%{$query}%")
        ->orWhere('contact_no', 'LIKE', "%{$query}%")
        ->orWhere('whatsapp_no', 'LIKE', "%{$query}%")
        ->limit(10)
        ->get();

    return response()->json($customers);
}

}
