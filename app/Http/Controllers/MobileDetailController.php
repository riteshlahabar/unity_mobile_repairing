<?php

namespace App\Http\Controllers;

use App\Services\MobileDetailService;
use Illuminate\Http\Request;
use App\Imports\BulkMasterImport;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Mobile Detail Controller
 * 
 * Handles mobile device details (companies, models, colors, series, technicians)
 * 
 * @see \App\Repositories\Contracts\MobileDetailRepositoryInterface - Data access
 * @see \App\Services\MobileDetailService - Business logic
 * 
 * Dependencies injected via constructor:
 * - MobileDetailService $service
 */
class MobileDetailController extends Controller
{
    public function __construct(
        protected MobileDetailService $service
    ) {}

    // ========================================
    // STORE METHODS
    // ========================================

    /**
     * Store a new company
     */
    public function storeCompany(Request $request)
    {
        $request->validate(['company' => 'required|string|max:255']);
        
        $result = $this->service->storeEntity('company', $request->company);
        return response()->json($result);
    }

    /**
     * Store a new model
     */
    public function storeModel(Request $request)
    {
        $request->validate(['model' => 'required|string|max:255']);
        
        $result = $this->service->storeEntity('model', $request->model);
        return response()->json($result);
    }

    /**
     * Store a new color
     */
    public function storeColor(Request $request)
    {
        $request->validate(['color' => 'required|string|max:255']);
        
        $result = $this->service->storeEntity('color', $request->color);
        return response()->json($result);
    }

    /**
     * Store a new series
     */
    public function storeSeries(Request $request)
    {
        $request->validate(['series' => 'required|string|max:255']);
        
        $result = $this->service->storeEntity('series', $request->series);
        return response()->json($result);
    }

    /**
     * Store a new technician
     */
    public function storeTechnician(Request $request)
    {
        $request->validate(['technician' => 'required|string|max:255']);
        
        $result = $this->service->storeEntity('technician', $request->technician);
        return response()->json($result);
    }

    // ========================================
    // FETCH METHODS
    // ========================================

    /**
     * Fetch all companies
     */
    public function fetchCompanies()
    {
        $companies = $this->service->fetchEntities('company');
        return response()->json($companies);
    }

    /**
     * Fetch all models
     */
    public function fetchModels()
    {
        $models = $this->service->fetchEntities('model');
        return response()->json($models);
    }

    /**
     * Fetch all colors
     */
    public function fetchColors()
    {
        $colors = $this->service->fetchEntities('color');
        return response()->json($colors);
    }

    /**
     * Fetch all series
     */
    public function fetchSeries()
    {
        $series = $this->service->fetchEntities('series');
        return response()->json($series);
    }

    /**
     * Fetch all technicians
     */
    public function fetchTechnicians()
    {
        $technicians = $this->service->fetchEntities('technician');
        return response()->json($technicians);
    }
    
    public function updateCompany(Request $request)
{
    $request->validate(['old_company' => 'required|string', 'company' => 'required|string|max:255']);

    // Example update logic:
    $updated = \DB::table('companies')
        ->where('name', $request->old_company)
        ->update(['name' => $request->company]);

    return response()->json(['success' => (bool)$updated, 'updatedValue' => $request->company]);
}

public function updateModel(Request $request)
{
    $request->validate(['old_model' => 'required|string', 'model' => 'required|string|max:255']);
    $updated = \DB::table('models')
        ->where('name', $request->old_model)
        ->update(['name' => $request->model]);
    return response()->json(['success' => (bool)$updated, 'updatedValue' => $request->model]);
}

public function updateColor(Request $request)
{
    $request->validate(['old_color' => 'required|string', 'color' => 'required|string|max:255']);
    $updated = \DB::table('colors')
        ->where('name', $request->old_color)
        ->update(['name' => $request->color]);
    return response()->json(['success' => (bool)$updated, 'updatedValue' => $request->color]);
}

public function updateSeries(Request $request)
{
    $request->validate(['old_series' => 'required|string', 'series' => 'required|string|max:255']);
    $updated = \DB::table('series')
        ->where('name', $request->old_series)
        ->update(['name' => $request->series]);
    return response()->json(['success' => (bool)$updated, 'updatedValue' => $request->series]);
}

public function updateTechnician(Request $request)
{
    $request->validate(['old_technician' => 'required|string', 'technician' => 'required|string|max:255']);
    $updated = \DB::table('technicians')
        ->where('name', $request->old_technician)
        ->update(['name' => $request->technician]);
    return response()->json(['success' => (bool)$updated, 'updatedValue' => $request->technician]);
}

public function bulkMasterUpload(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx,xls'
    ]);

    // Example using Laravel-Excel:
    try {
        $import = new \App\Imports\BulkMasterImport;
        \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('file'));

        return response()->json(['success' => true, 'message' => 'Data imported!']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}


}
