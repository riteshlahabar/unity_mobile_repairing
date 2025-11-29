<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Services\CustomerService;
use Illuminate\Support\Facades\Log;

/**
 * Customer Controller
 * 
 * Handles HTTP requests for Customer operations
 * 
 * @see \App\Repositories\Contracts\CustomerRepositoryInterface - Data access
 * @see \App\Services\CustomerService - Business logic
 * 
 * Dependencies injected via constructor:
 * - CustomerRepositoryInterface $repository
 * - CustomerService $service
 */
class CustomerController extends Controller
{
    public function __construct(
        protected CustomerRepositoryInterface $repository,
        protected CustomerService $service
    ) {}

    /**
     * Show all customers (today's delivered + non-delivered)
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $customers = $search 
            ? $this->repository->getTodayDeliveredOrNonDeliveredWithSearch($search, 20)
            : $this->repository->getTodayDeliveredOrNonDelivered(20);

        return view('customers.index', compact('customers', 'search'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Check if customer exists by contact number (AJAX)
     */
    public function checkContact(Request $request)
    {
        $contactNo = $request->input('contact_no');
        $result = $this->service->checkContactExists($contactNo);
        
        return response()->json($result);
    }

    /**
     * Store new customer
     */
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

            $result = $this->service->createCustomer($validated);

            return response()->json($result, 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Customer creation error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show single customer with jobsheets
     */
    public function show($id)
    {
        $customer = $this->repository->findByCustomerId($id);
        $jobSheets = $customer->jobSheets()->latest()->paginate(10);
        
        return view('customers.show', compact('customer', 'jobSheets'));
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $customer = $this->repository->findByCustomerId($id);
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update customer
     */
    public function update(Request $request, $id)
    {
        try {
            $customer = $this->repository->findByCustomerId($id);

            $validated = $request->validate([
                'full_name' => 'required|string|max:255',
                'address' => 'required|string',
                'contact_no' => 'required|digits:10|regex:/^[6-9][0-9]{9}$/|unique:customers,contact_no,' . $customer->id,
                'alternate_no' => 'nullable|digits:10|regex:/^[6-9][0-9]{9}$/',
                'whatsapp_no' => 'required|digits:10|regex:/^[6-9][0-9]{9}$/',
            ]);

            $result = $this->service->updateCustomer($id, $validated);

            if ($request->expectsJson()) {
                return response()->json($result);
            }

            return redirect()->route('customers.index')->with('success', $result['message']);

        } catch (\Exception $e) {
            Log::error('Customer update error: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Update failed')->withInput();
        }
    }

    /**
     * Delete customer
     */
    public function destroy($id)
    {
        try {
            $this->service->deleteCustomer($id);
            return redirect()->route('customers.index')->with('success', 'Customer deleted successfully!');
            
        } catch (\Exception $e) {
            Log::error('Customer delete error: ' . $e->getMessage());
            return back()->with('error', 'Delete failed');
        }
    }

    /**
     * Search customers (AJAX)
     */
    public function search(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json([]);
        }

        $customers = $this->repository->searchQuery($query, 10);
        return response()->json($customers);
    }
}
