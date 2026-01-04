# 05. Frontend Implementation

The frontend is built using Laravel Blade templates, likely with Bootstrap 5 or Tailwind CSS.

## 1. Main Layout
The `app.blade.php` layout acts as the shell. It includes the Sidebar, Topbar, and Main Content area.

**File:** `resources/views/layouts/app.blade.php`
```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>{{ config('app.name', 'HMS') }}</title>
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 flex">
        
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Main Content -->
        <main class="flex-1 p-6">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>
</body>
</html>
```

## 2. Dynamic Sidebar (Role-Based)
The sidebar renders links based on the user's role using the `@role` directive we created.

**File:** `resources/views/layouts/sidebar.blade.php`
```blade
<div class="w-64 bg-white border-r h-full">
    <div class="p-4 font-bold text-lg">HMS Pro</div>
    
    <nav class="mt-4">
        <!-- Common -->
        <a href="{{ route('dashboard') }}" class="block p-3 hover:bg-gray-50">Dashboard</a>

        <!-- Doctor Menu -->
        @role('Doctor')
            <div class="text-xs font-semibold text-gray-500 uppercase mt-4 px-3">Clinical</div>
            <a href="{{ route('appointments.index') }}" class="block p-3 hover:bg-gray-50">My Appointments</a>
            <a href="{{ route('patients.index') }}" class="block p-3 hover:bg-gray-50">Patients</a>
            <a href="{{ route('prescriptions.create') }}" class="block p-3 hover:bg-gray-50">Write Prescription</a>
        @endrole

        <!-- Nurse Menu -->
        @role('Nurse')
            <div class="text-xs font-semibold text-gray-500 uppercase mt-4 px-3">Ward</div>
            <a href="{{ route('ipd.admissions.index') }}" class="block p-3 hover:bg-gray-50">Admissions</a>
            <a href="{{ route('ipd.beds.index') }}" class="block p-3 hover:bg-gray-50">Bed Status</a>
        @endrole

        <!-- Pharmacist Menu -->
        @role('Pharmacist')
            <div class="text-xs font-semibold text-gray-500 uppercase mt-4 px-3">Pharmacy</div>
            <a href="{{ route('pharmacy.sales.create') }}" class="block p-3 hover:bg-gray-50">POS (Sale)</a>
            <a href="{{ route('pharmacy.inventory.index') }}" class="block p-3 hover:bg-gray-50">Inventory</a>
        @endrole

        <!-- Admin Menu -->
        @role('Super Admin|Clinic Admin')
            <div class="text-xs font-semibold text-gray-500 uppercase mt-4 px-3">Administration</div>
            <a href="{{ route('users.index') }}" class="block p-3 hover:bg-gray-50">User Management</a>
            <a href="{{ route('reports.index') }}" class="block p-3 hover:bg-gray-50">Reports</a>
        @endrole
    </nav>
</div>
```

## 3. Example View: Appointment Booking
A typical form view.

**File:** `resources/views/appointments/create.blade.php`
```blade
<x-app-layout>
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Book Appointment</h2>

        <form action="{{ route('appointments.store') }}" method="POST">
            @csrf

            <!-- Patient Selection -->
            <div class="mb-4">
                <label>Patient</label>
                <select name="patient_id" class="form-control">
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->name }} ({{ $patient->uhid }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Doctor Selection -->
            <div class="mb-4">
                <label>Doctor</label>
                <select name="doctor_id" id="doctor_select" class="form-control">
                    <!-- Populated via AJAX or backend -->
                </select>
            </div>

            <!-- Date -->
            <div class="mb-4">
                <label>Date</label>
                <input type="date" name="appointment_date" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Book Now</button>
        </form>
    </div>
</x-app-layout>
```
