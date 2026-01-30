<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * AuthenticatedSessionController
 *
 * Handles the login and logout processes for the application.
 * Manages session creation and destruction.
 */
class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'clinic_id' => Auth::user()->clinic_id ?? null,
            'action' => 'login',
            'description' => 'User logged in',
            'entity_type' => 'App\Models\User',
            'entity_id' => Auth::id(),
            'ip_address' => $request->ip(),
        ]);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if ($user) {
            ActivityLog::create([
                'user_id' => $user->id,
                'clinic_id' => $user->clinic_id ?? null,
                'action' => 'logout',
                'description' => 'User logged out',
                'entity_type' => 'App\Models\User',
                'entity_id' => $user->id,
                'ip_address' => $request->ip(),
            ]);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
