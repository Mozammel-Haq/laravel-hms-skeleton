# Class 36: Patient Profile View

## Introduction
The Patient Profile is the dashboard for a specific patient. It needs to aggregate everything: Demographics, Vitals, History, and Appointments.

## 1. The Controller Method
```php
public function show(Patient $patient)
{
    // Eager load everything needed for the view
    $patient->load([
        'allergies', 
        'conditions', 
        'vitals' => function($q) { $q->latest()->limit(5); },
        // 'appointments.doctor' // Future module
    ]);
    
    return view('patients.show', compact('patient'));
}
```

## 2. The Blade View Structure
We use a Tabbed interface.

`resources/views/patients/show.blade.php`:

```html
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">{{ $patient->name }} ({{ $patient->age }} yrs)</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-6">
            
            <!-- Sidebar: Demographics -->
            <div class="col-span-1 bg-white p-4 rounded shadow">
                <img src="{{ $patient->photo_url }}" class="w-32 h-32 rounded-full mx-auto mb-4">
                <p><strong>Phone:</strong> {{ $patient->phone }}</p>
                <p><strong>Blood Group:</strong> <span class="badge badge-danger">{{ $patient->blood_group }}</span></p>
                <div class="mt-4">
                    <h4 class="font-bold text-red-600">Allergies</h4>
                    <ul>
                        @foreach($patient->allergies as $allergy)
                            <li>{{ $allergy->allergen }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Main Content: Tabs -->
            <div class="col-span-3 bg-white p-4 rounded shadow">
                <div x-data="{ tab: 'vitals' }">
                    <div class="border-b">
                        <button @click="tab = 'vitals'" :class="{ 'border-blue-500': tab === 'vitals' }">Vitals</button>
                        <button @click="tab = 'history'" :class="{ 'border-blue-500': tab === 'history' }">Medical History</button>
                        <button @click="tab = 'appointments'">Appointments</button>
                    </div>

                    <div x-show="tab === 'vitals'">
                        <!-- Vitals Table / Chart -->
                        @include('patients.partials.vitals-table')
                    </div>
                    
                    <div x-show="tab === 'history'">
                        <!-- Conditions & Surgeries -->
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
```

## Summary
The Profile View is the "Cockpit" for the doctor. It must be clean, fast, and present critical information (like allergies) prominently.
