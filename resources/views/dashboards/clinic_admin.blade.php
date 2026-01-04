<x-app-layout>
    <div class="page-header d-flex justify-content-between align-items-center">
        <div class="page-title">
            <h4>Clinic Admin Dashboard</h4>
            <p>Overview of {{ auth()->user()->clinic->name ?? 'Clinic' }} operations</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Departments</h5>
                    <h3>{{ \App\Models\Department::count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Doctors</h5>
                    <h3>{{ \App\Models\Doctor::count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Patients</h5>
                    <h3>{{ \App\Models\Patient::count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Staff</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Recent Appointments</h5>
                </div>
                <div class="card-body">
                    <!-- Placeholder for Appointment List -->
                    <p>No recent appointments.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Clinic Performance</h5>
                </div>
                <div class="card-body">
                    <!-- Placeholder for Chart -->
                    <div id="clinic-chart"></div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>