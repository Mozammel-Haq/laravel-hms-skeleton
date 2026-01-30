<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelComeMail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

/**
 * RegisteredUserController
 *
 * Handles the registration of new users.
 * Validates input, creates user accounts, assigns default clinic, and triggers welcome events.
 */
class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Ensure clinic_id is set (mirror UserFactory logic or use default)
        // In a real app, registration might select a clinic or create one.
        // For this skeleton, we assign to the first clinic or create one.
        $clinicId = \App\Models\Clinic::query()->value('id') ?? \App\Models\Clinic::create([
            'name' => 'Default Clinic',
            'code' => 'DEF-001',
            'address_line_1' => '123 Default St',
            'city' => 'Default City',
            'country' => 'Default Country',
            'timezone' => 'UTC',
            'currency' => 'USD',
        ])->id;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'clinic_id' => $clinicId,
        ]);

        event(new Registered($user));
        Mail::to($request->email)->send(new WelComeMail($user));

        // Use attempt to ensure session is correctly established with all guards/scopes
        Auth::attempt($request->only('email', 'password'));
        $request->session()->regenerate();

        return redirect(route('dashboard', absolute: false));
    }
}
