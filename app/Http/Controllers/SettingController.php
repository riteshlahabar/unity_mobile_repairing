<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    /**
     * Display the settings page with existing data.
     */
    public function index()
    {
        $setting = DB::table('business_info')->first();
        $general = DB::table('general_info')->first();

        // Merge objects for easier access in the view, prioritizing business_info
        $settingsData = (object) array_merge(
            (array) $general,
            (array) $setting
        );

        return view('settings.index', ['setting' => $settingsData]);
    }

    /**
     * Save or update Business Info.
     */
    public function updateBusinessInfo(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'owner_name' => 'nullable|string|max:255',
            'mobile_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        $data = $request->only(['business_name', 'owner_name', 'mobile_number', 'email', 'address']);
        $data['updated_at'] = now();

        $existing = DB::table('business_info')->first();

        if ($existing) {
            DB::table('business_info')->where('id', $existing->id)->update($data);
            $message = 'Business Info Updated Successfully.';
        } else {
            $data['created_at'] = now();
            DB::table('business_info')->insert($data);
            $message = 'Business Info Saved Successfully.';
        }

        return response()->json(['success' => true, 'message' => $message]);
    }

    /**
     * Save or update Terms and Conditions.
     */
    public function updateTermsConditions(Request $request)
    {
        $request->validate(['terms_conditions' => 'nullable|string']);
        
        $data = ['terms_conditions' => $request->terms_conditions, 'updated_at' => now()];
        $existing = DB::table('general_info')->first();

        if ($existing) {
            DB::table('general_info')->where('id', $existing->id)->update($data);
            $message = 'Terms & Conditions Updated Successfully.';
        } else {
            $data['created_at'] = now();
            DB::table('general_info')->insert($data);
            $message = 'Terms & Conditions Saved Successfully.';
        }
        return response()->json(['success' => true, 'message' => $message]);
    }

    /**
     * Save or update Remarks.
     */
    public function updateRemarks(Request $request)
    {
        $request->validate(['remarks' => 'nullable|string']);
        
        $data = ['remarks' => $request->remarks, 'updated_at' => now()];
        $existing = DB::table('general_info')->first();

        if ($existing) {
            DB::table('general_info')->where('id', $existing->id)->update($data);
            $message = 'Remarks Updated Successfully.';
        } else {
            $data['created_at'] = now();
            DB::table('general_info')->insert($data);
            $message = 'Remarks Saved Successfully.';
        }
        return response()->json(['success' => true, 'message' => $message]);
    }

    /**
     * Save or update Logo.
     */
    public function updateLogo(Request $request)
    {
        $request->validate(['logo' => 'nullable|image|max:2048']);
        
        $path = null;
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
        }

        $data = ['logo' => $path, 'updated_at' => now()];
        $existing = DB::table('general_info')->first();

        if ($existing) {
            DB::table('general_info')->where('id', $existing->id)->update($data);
            $message = 'Logo Updated Successfully.';
        } else {
            $data['created_at'] = now();
            $data['logo'] = $path; // Ensure path is set for insert
            DB::table('general_info')->insert($data);
            $message = 'Logo Saved Successfully.';
        }
        return response()->json([
    'success' => true,
    'message' => $message,
    'image_url' => $path ? asset('storage/' . $path) : null,
]);
    }
    
    /**
     * Save or update Profile Picture.
     */
    public function updateProfilePicture(Request $request)
    {
        $request->validate(['profile_picture' => 'nullable|image|max:2048']);

        $path = null;
        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
        }
        
        $data = ['profile_picture' => $path, 'updated_at' => now()];
        $existing = DB::table('general_info')->first();

        if ($existing) {
            DB::table('general_info')->where('id', $existing->id)->update($data);
            $message = 'Profile Picture Updated Successfully.';
        } else {
            $data['created_at'] = now();
            DB::table('general_info')->insert($data);
            $message = 'Profile Picture Saved Successfully.';
        }
        return response()->json([
    'success' => true,
    'message' => $message,
    'image_url' => $path ? asset('storage/' . $path) : null,
]);
    }

    /**
     * Save or update Unity Signature.
     */
    public function updateUnitySignature(Request $request)
    {
        $request->validate(['unity_signature' => 'nullable|image|max:2048']);
        
        $path = null;
        if ($request->hasFile('unity_signature')) {
            $path = $request->file('unity_signature')->store('unity_signatures', 'public');
        }
        
        $data = ['unity_signature' => $path, 'updated_at' => now()];
        $existing = DB::table('general_info')->first();

        if ($existing) {
            DB::table('general_info')->where('id', $existing->id)->update($data);
            $message = 'Unity Signature Updated Successfully.';
        } else {
            $data['created_at'] = now();
            DB::table('general_info')->insert($data);
            $message = 'Unity Signature Saved Successfully.';
        }
        return response()->json([
    'success' => true,
    'message' => $message,
    'image_url' => $path ? asset('storage/' . $path) : null,
]);
    }

    /**
     * Update user password.
     */
    public function updateSecurity(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|confirmed|min:6',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Current password does not match.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['success' => true, 'message' => 'Password Changed Successfully.']);
    }
}
