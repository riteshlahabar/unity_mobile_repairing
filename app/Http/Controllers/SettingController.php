<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    // Display the settings page
    public function index()
    {
        // Load current settings here if needed and pass to view
        return view('settings.index');
    }

    // Handle update of general settings
    public function update(Request $request)
    {
        // Add validation and update logic here

        // Redirect back to settings page with success message
        return redirect()->route('setting.index')->with('success', 'Settings updated successfully!');
    }

    // Handle logo update
    public function updateLogo(Request $request)
    {
        // Add validation and file upload logic for logo here
        
        return redirect()->route('setting.index')->with('success', 'Logo updated successfully!');
    }

    // Handle profile picture update
    public function updateProfilePicture(Request $request)
    {
        // Add validation and file upload logic for profile picture here
        
        return redirect()->route('setting.index')->with('success', 'Profile picture updated successfully!');
    }
}
