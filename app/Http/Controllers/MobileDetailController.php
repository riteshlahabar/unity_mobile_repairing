<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MobileDetailController extends Controller
{
    // Store Company
public function storeCompany(Request $request)
{
    $request->validate(['company' => 'required|string|max:255']);

    $exists = DB::table('companies')->whereRaw('LOWER(name) = ?', [strtolower($request->company)])->exists();

    if ($exists) {
        return response()->json(['success' => false, 'message' => 'Company already exists']);
    }

    DB::table('companies')->insert([
        'name' => $request->company,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return response()->json(['success' => true, 'message' => 'Company added successfully']);
}

// Store Model
public function storeModel(Request $request)
{
    $request->validate(['model' => 'required|string|max:255']);

    $exists = DB::table('models')->whereRaw('LOWER(name) = ?', [strtolower($request->model)])->exists();

    if ($exists) {
        return response()->json(['success' => false, 'message' => 'Model already exists']);
    }

    DB::table('models')->insert([
        'name' => $request->model,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return response()->json(['success' => true, 'message' => 'Model added successfully']);
}

// Store Color
public function storeColor(Request $request)
{
    $request->validate(['color' => 'required|string|max:255']);

    $exists = DB::table('colors')->whereRaw('LOWER(name) = ?', [strtolower($request->color)])->exists();

    if ($exists) {
        return response()->json(['success' => false, 'message' => 'Color already exists']);
    }

    DB::table('colors')->insert([
        'name' => $request->color,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return response()->json(['success' => true, 'message' => 'Color added successfully']);
}

// Store Series
public function storeSeries(Request $request)
{
    $request->validate(['series' => 'required|string|max:255']);

    $exists = DB::table('series')->whereRaw('LOWER(name) = ?', [strtolower($request->series)])->exists();

    if ($exists) {
        return response()->json(['success' => false, 'message' => 'Series already exists']);
    }

    DB::table('series')->insert([
        'name' => $request->series,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return response()->json(['success' => true, 'message' => 'Series added successfully']);
}

// Store Technician
public function storeTechnician(Request $request)
{
    $request->validate(['technician' => 'required|string|max:255']);

    $exists = DB::table('technicians')->whereRaw('LOWER(name) = ?', [strtolower($request->technician)])->exists();

    if ($exists) {
        return response()->json(['success' => false, 'message' => 'Technician already exists']);
    }

    DB::table('technicians')->insert([
        'name' => $request->technician,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return response()->json(['success' => true, 'message' => 'Technician added successfully']);
}

    // Fetch Companies
    public function fetchCompanies()
    {
        $companies = DB::table('companies')
            ->orderBy('name')
            ->pluck('name');

        return response()->json($companies);
    }

    // Fetch Models
    public function fetchModels()
    {
        $models = DB::table('models')
            ->orderBy('name')
            ->pluck('name');

        return response()->json($models);
    }

    // Fetch Colors
    public function fetchColors()
    {
        $colors = DB::table('colors')
            ->orderBy('name')
            ->pluck('name');

        return response()->json($colors);
    }

    // Fetch Series
    public function fetchSeries()
    {
        $series = DB::table('series')
            ->orderBy('name')
            ->pluck('name');

        return response()->json($series);
    }

    // Fetch Technicians
    public function fetchTechnicians()
    {
        $technicians = DB::table('technicians')
            ->orderBy('name')
            ->pluck('name');

        return response()->json($technicians);
    }
}
