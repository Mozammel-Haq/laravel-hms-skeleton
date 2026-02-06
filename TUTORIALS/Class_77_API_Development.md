# Class 77: API Development (Sanctum)

## Introduction
We might want a mobile app for patients to view their prescriptions.

## 1. Setup Sanctum
Laravel Sanctum provides a lightweight authentication system for SPAs and simple APIs.

```bash
php artisan install:api
```

## 2. API Routes
In `routes/api.php`.

```php
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/patient/prescriptions', [Api\PrescriptionController::class, 'index']);
});
```

## 3. Login Endpoint
Mobile apps send credentials and get a token.

```php
public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);
 
    $user = User::where('email', $request->email)->first();
 
    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }
 
    $token = $user->createToken('mobile-app')->plainTextToken;
 
    return response()->json(['token' => $token]);
}
```

## Summary
APIs extend the reach of your HMS beyond the web browser.
