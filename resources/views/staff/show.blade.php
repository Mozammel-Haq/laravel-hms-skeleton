<x-app-layout>
    <div class="container-fluid mx-2">
        <div class="card mt-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="page-title mb-0">Staff Details</h3>
                    <a href="{{ route('staff.index') }}" class="btn btn-outline-secondary">Back</a>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Profile</h5>
                                <div class="mb-2"><strong>Name:</strong> {{ $staff->name }}</div>
                                <div class="mb-2"><strong>Email:</strong> {{ $staff->email }}</div>
                                <div class="mb-2"><strong>Clinic ID:</strong> {{ $staff->clinic_id }}</div>
                                <div class="mb-2"><strong>Roles:</strong>
                                    {{ $staff->roles->pluck('name')->implode(', ') }}</div>
                                <div class="mt-3">
                                    <a href="{{ route('staff.edit', $staff->id) }}" class="btn btn-primary">Edit</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</x-app-layout>
