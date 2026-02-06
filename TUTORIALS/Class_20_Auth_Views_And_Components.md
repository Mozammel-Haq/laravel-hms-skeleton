# Class 20: Auth Views & Components

## Introduction
We have the backend logic. Now we need the frontend. Since we are rebuilding, we will use Blade components for a clean UI.

## 1. The Login View
Open `resources/views/auth/login.blade.php`.

```html
<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div>
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-button class="ms-4">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
```

## 2. The Register View (Modified)
We need to add the `clinic_code` field to the register view.
Open `resources/views/auth/register.blade.php`.

```html
<!-- ... inside the form ... -->

<!-- Name -->
<div>
    <x-label for="name" value="{{ __('Name') }}" />
    <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
</div>

<!-- Email -->
<div class="mt-4">
    <x-label for="email" value="{{ __('Email') }}" />
    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
</div>

<!-- Clinic Code (New Field) -->
<div class="mt-4">
    <x-label for="clinic_code" value="{{ __('Clinic Code') }}" />
    <x-input id="clinic_code" class="block mt-1 w-full" type="text" name="clinic_code" :value="old('clinic_code')" required placeholder="e.g. MAYO001" />
    <p class="text-xs text-gray-500 mt-1">Enter the code provided by your hospital.</p>
</div>

<!-- ... Password and Confirm Password ... -->
```

## 3. Styling
We assume you are using Tailwind CSS (standard with Breeze/Jetstream). If you are using Bootstrap, simply replace `<x-input>` with `<input class="form-control">` and adjust the classes accordingly.

## Summary
We have completed the frontend integration for our custom Auth logic.

## Module 3 Completion
Congratulations! You have completed Module 3. You now have:
-   **Custom User Model**: Ready for HMS profiles.
-   **RBAC System**: Roles, Permissions, and Policies.
-   **Secure Auth Flow**: Tenant-aware registration.

In Module 4, we will start building the actual entities of the hospital, starting with Departments.
