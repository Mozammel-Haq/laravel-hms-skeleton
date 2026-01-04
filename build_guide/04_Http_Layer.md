# 04. HTTP Layer (Controllers & API)

Controllers act as the entry point. They validate requests and delegate logic to Services.

## 1. Dependency Injection
We inject Services into Controllers via the constructor.

**Example:** `app/Http/Controllers/PharmacyController.php`

```php
namespace App\Http\Controllers;

use App\Services\PharmacyService;
use App\Models\Patient;
use Illuminate\Http\Request;

class PharmacyController extends Controller
{
    protected $pharmacyService;

    public function __construct(PharmacyService $pharmacyService)
    {
        $this->pharmacyService = $pharmacyService;
    }

    public function storeSale(Request $request)
    {
        // 1. Validate
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'items' => 'required|array',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            // 2. Delegate to Service
            $patient = Patient::findOrFail($validated['patient_id']);
            
            $sale = $this->pharmacyService->processSale(
                $patient, 
                $validated['items']
            );

            return redirect()->route('pharmacy.sales.show', $sale)
                ->with('success', 'Sale processed successfully');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
```

## 2. API Routes
Define routes in `routes/web.php` (for web app) or `routes/api.php` (for mobile/external).

**File:** `routes/web.php`

```php
use App\Http\Controllers\PharmacyController;

Route::middleware(['auth', 'verified', 'clinic.context'])->group(function () {
    
    // Pharmacy Routes
    Route::middleware(['role:Pharmacist|Super Admin'])->group(function () {
        Route::get('/pharmacy/sales/create', [PharmacyController::class, 'create'])->name('pharmacy.sales.create');
        Route::post('/pharmacy/sales', [PharmacyController::class, 'storeSale'])->name('pharmacy.sales.store');
    });

});
```

## 3. Form Requests
For complex validations, use Form Request classes.

**File:** `app/Http/Requests/StoreAppointmentRequest.php`

```php
public function rules(): array
{
    return [
        'doctor_id' => 'required|exists:users,id',
        'appointment_date' => 'required|date|after_or_equal:today',
        'time_slot' => 'required|date_format:H:i',
        'reason' => 'nullable|string|max:500',
    ];
}
```
