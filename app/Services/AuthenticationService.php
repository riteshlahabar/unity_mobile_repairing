<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthenticationService
{
    /**
     * Attempt to authenticate user with credentials
     * 
     * @param array $credentials
     * @param bool $remember
     * @return bool
     */
    public function attemptLogin(array $credentials, bool $remember = false): bool
    {
        return Auth::attempt($credentials, $remember);
    }

    /**
     * Logout the authenticated user
     * 
     * @return void
     */
    public function logout(): void
    {
        Auth::logout();
    }

    /**
     * Get the authenticated user
     * 
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function getAuthenticatedUser()
    {
        return Auth::user();
    }

    /**
     * Check if user is authenticated
     * 
     * @return bool
     */
    public function isAuthenticated(): bool
    {
        return Auth::check();
    }

    /**
     * Regenerate session after login
     * 
     * @param Request $request
     * @return void
     */
    public function regenerateSession(Request $request): void
    {
        $request->session()->regenerate();
    }

    /**
     * Invalidate session and regenerate token
     * 
     * @param Request $request
     * @return void
     */
    public function invalidateSession(Request $request): void
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
