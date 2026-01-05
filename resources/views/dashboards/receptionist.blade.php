<x-app-layout>
                    <div class="content pb-0">
    <div class="page-header d-flex justify-content-between align-items-center">
        <div class="page-title">
            <h4>Receptionist Dashboard</h4>
            <p>Front Desk Operations</p>
        </div>
        <a href="{{ route('patients.create') }}" class="btn btn-primary"><i class="ti ti-plus"></i> New Patient</a>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Today’s Appointments</h5>
                    <h3>{{ \App\Models\Appointment::whereDate('appointment_date', today())->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Checked In</h5>
                    <h3>{{ \App\Models\Appointment::whereDate('appointment_date', today())->where('status', 'Checked In')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>New Patients</h5>
                    <h3>{{ \App\Models\Patient::whereDate('created_at', today())->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Appointment Queue</h5>
                </div>
                <div class="card-body">
                    <!-- Placeholder for Queue -->
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Dynamic content here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>
