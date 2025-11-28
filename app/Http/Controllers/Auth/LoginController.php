<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthenticationService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Login Controller
 * 
 * Handles user authentication (login/logout)
 * 
 * @see \App\Services\AuthenticationService - Authentication business logic
 * 
 * Dependencies injected via constructor:
 * - AuthenticationService $authService
 */
class LoginController extends Controller
{
    public function __construct(
        protected AuthenticationService $authService
    ) {}

    /**
     * Show the login form
     * 
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    /**
     * Handle login attempt
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        // Validate credentials
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Get remember me preference
        $remember = $request->boolean('remember');

        // Attempt login
        if ($this->authService->attemptLogin($credentials, $remember)) {
            // Regenerate session for security
            $this->authService->regenerateSession($request);

            // Redirect to intended page or dashboard
            return redirect()->intended(route('dashboard'));
        }

        // Login failed - throw validation exception
        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    /**
     * Handle logout
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // Logout user
        $this->authService->logout();

        // Invalidate session and regenerate CSRF token
        $this->authService->invalidateSession($request);

        // Redirect to login page
        return redirect()->route('admin.login');
    }
}
