# Class 17: Custom Auth Controllers

## Introduction
Laravel Breeze or Jetstream are great, but for a complex HMS, we often need full control over the authentication flow. We will look at how to customize the `RegisteredUserController` to handle our specific logic (like assigning a default role or linking to a clinic).

## 1. The Registration Controller
Open (or create) `app/Http/Controllers/Auth/RegisteredUserController.php`.

```php
public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        // HMS Specifics
        'phone' => ['nullable', 'string', 'max:20'],
        'clinic_code' => ['required', 'exists:clinics,code'], // Users must register under a clinic
    ]);

    // Find the clinic
    $clinic = \App\Models\Clinic::where('code', $request->clinic_code)->firstOrFail();

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'phone' => $request->phone,
        'clinic_id' => $clinic->id, // Assign Tenant
        'status' => 'active',
    ]);

    // Assign Default Role (e.g., Patient)
    // In a real app, staff are usually created by Admins, not self-registration.
    // So self-registration is usually for Patients.
    $patientRole = \App\Models\Role::where('name', 'patient')->first();
    if ($patientRole) {
        $user->roles()->attach($patientRole);
    }

    event(new Registered($user));

    Auth::login($user);

    return redirect(RouteServiceProvider::HOME);
}
```

## 2. Why 'clinic_code'?
We use a `clinic_code` (e.g., "MAYO001") instead of a dropdown of IDs because:
1.  **Security**: We don't want to expose our entire client list in a dropdown.
2.  **UX**: Patients are usually given a code by the front desk. "Go to hms.com/register and enter code MAYO001".

## Summary
We customized the registration flow to enforce Multi-Tenancy (assigning `clinic_id`) and RBAC (assigning `patient` role) immediately upon creation.

In the next class, we will secure our application logic using Policies.
