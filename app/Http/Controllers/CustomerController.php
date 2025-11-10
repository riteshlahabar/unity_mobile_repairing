<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // Show all customers
    public function index()
    {
        return view('customers.index');
    }

    // Show create form
    public function create()
    {
        return view('customers.create');
    }

    // Store new customer
    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'customer_id' => 'required|unique:customers',
            'full_name' => 'required|string|max:255',
            'address' => 'required|string',
            'contact_no' => 'required|digits:10',
            'alternate_no' => 'nullable|digits:10',
            'whatsapp_no' => 'required|digits:10',
        ]);

        // Store in database (add your logic here)
        // Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'Customer created successfully!');
    }
    // Show single customer
    public function show($id)
    {
        return view('customers.show', compact('id'));
    }

    // Show edit form
    public function edit($id)
    {
        return view('customers.edit', compact('id'));
    }

    // Update customer
    public function update(Request $request, $id)
    {
        // Add your logic here
        return redirect()->route('customers.index')->with('success', 'Customer updated successfully!');
    }

    // Delete customer
    public function destroy($id)
    {
        // Add your logic here
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully!');
    }
}
