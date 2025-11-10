<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JobSheetController extends Controller
{
    // Show all jobsheets
    public function index()
    {
        return view('jobsheets.index');
    }

    // Show create form
    public function create()
    {
        return view('jobsheets.create');
    }

    // Store new jobsheet
    public function store(Request $request)
    {
        // Add your logic here
        return redirect()->route('jobsheets.index')->with('success', 'JobSheet created successfully!');
    }

    // Show single jobsheet
    public function show($id)
    {
        return view('jobsheets.show', compact('id'));
    }

    // Show edit form
    public function edit($id)
    {
        return view('jobsheets.edit', compact('id'));
    }

    // Update jobsheet
    public function update(Request $request, $id)
    {
        // Add your logic here
        return redirect()->route('jobsheets.index')->with('success', 'JobSheet updated successfully!');
    }

    // Delete jobsheet
    public function destroy($id)
    {
        // Add your logic here
        return redirect()->route('jobsheets.index')->with('success', 'JobSheet deleted successfully!');
    }

    // Mark as complete
    public function markComplete($id)
    {
        // Add your logic here
        return redirect()->route('jobsheets.index')->with('success', 'JobSheet marked as complete!');
    }

    // Mark as delivered
    public function markDelivered($id)
    {
        // Add your logic here
        return redirect()->route('jobsheets.index')->with('success', 'JobSheet marked as delivered!');
    }
}
