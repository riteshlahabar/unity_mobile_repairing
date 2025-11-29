<?php

namespace App\Http\Controllers;

use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
<<<<<<< HEAD
use Illuminate\Support\Facades\Auth;  
use Illuminate\Support\Facades\Hash;           // ← ADD THIS
use Illuminate\Validation\ValidationException; // ← ADD THIS
use App\Models\User;  
=======
>>>>>>> 0963cebdc0528a837022693382951a181cdac698

/**
 * Setting Controller
 * 
 * Handles application settings (business info, terms, remarks, security)
 * 
 * @see \App\Repositories\Contracts\SettingsRepositoryInterface - Data access
 * @see \App\Services\SettingsService - Business logic
 * 
 * Dependencies injected via constructor:
 * - SettingsService $service
 */
class SettingController extends Controller
{
    public function __construct(
        protected SettingsService $service
    ) {}

    /**
     * Display the settings page with existing data
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $setting = $this->service->getAllSettings();
        return view('settings.index', ['setting' => $setting]);
    }

    /**
     * Update business information
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateBusinessInfo(Request $request)
    {
        try {
            $validated = $request->validate([
                'business_name' => 'required|string|max:255',
                'owner_name' => 'nullable|string|max:255',
                'mobile_number' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                'address' => 'nullable|string',
            ]);

            $result = $this->service->updateBusinessInfo($validated);
            return response()->json($result);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Business Info Update Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating business info'
            ], 500);
        }
    }

    /**
     * Update terms and conditions
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTermsConditions(Request $request)
    {
        try {
            $request->validate(['terms_conditions' => 'nullable|string']);

            $result = $this->service->updateTermsConditions(
                $request->input('terms_conditions', '')
            );

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('Terms Update Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating terms & conditions'
            ], 500);
        }
    }

    /**
     * Update remarks
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateRemarks(Request $request)
    {
        try {
            $request->validate(['remarks' => 'nullable|string']);

            $result = $this->service->updateRemarks(
                $request->input('remarks', '')
            );

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('Remarks Update Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating remarks'
            ], 500);
        }
    }

    /**
     * Update user password
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSecurity(Request $request)
    {
        try {
            $validated = $request->validate([
                'current_password' => 'required|string',
                'new_password' => 'required|string|confirmed|min:6',
            ]);

            $result = $this->service->updatePassword(
                auth()->user(),
                $validated['current_password'],
                $validated['new_password']
            );
<<<<<<< HEAD

            return response()->json($result);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Password Update Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating password'
            ], 500);
        }
=======

            return response()->json($result);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Password Update Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating password'
            ], 500);
        }
    }
    
    /**
 * Change revenue PIN
 */
public function changeRevenuePin(Request $request)
{
    $request->validate([
        'current_pin' => 'required|string',
        'new_pin' => 'required|string|size:4|different:current_pin',
        'confirm_pin' => 'required|string|same:new_pin',
    ]);

    try {
        $user = auth()->user();

        // Verify current PIN
        if ($user->revenue_pin !== $request->current_pin) {
            return response()->json([
                'success' => false,
                'message' => 'Current PIN is incorrect'
            ], 422);
        }

        // Update PIN
        $user->update(['revenue_pin' => $request->new_pin]);

        // Update config
        config(['services.revenue_pin' => $request->new_pin]);

        Log::info('Revenue PIN changed by user', ['user_id' => $user->id]);

        return response()->json([
            'success' => true,
            'message' => 'Revenue PIN changed successfully'
        ]);

    } catch (\Exception $e) {
        Log::error('Error changing revenue PIN: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
>>>>>>> 0963cebdc0528a837022693382951a181cdac698
    }
    
       public function changePin(Request $request)
    {
        try {
            $request->validate([
                'current_pin' => 'required|string|size:4',
                'new_pin' => 'required|string|size:4',
                'new_pin_confirmation' => 'required|string|size:4|same:new_pin',
            ], [
                'current_pin.size' => 'Current PIN must be 4 digits',
                'new_pin.size' => 'New PIN must be 4 digits',
                'new_pin_confirmation.same' => 'PIN confirmation does not match',
            ]);

            $user = Auth::user();

            // Check current PIN
            if (!Hash::check($request->current_pin, $user->revenue_pin)) {
                throw ValidationException::withMessages([
                    'current_pin' => ['Current PIN is incorrect'],
                ]);
            }

            // Update PIN (hashed)
            $user->revenue_pin = Hash::make($request->new_pin);
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'PIN changed successfully!'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Change PIN error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to change PIN. Please try again.'
            ], 500);
        }
    }

}

}
