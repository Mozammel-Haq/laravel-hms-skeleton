<x-app-layout>
    <div class="page-header d-flex justify-content-between align-items-center">
        <div class="page-title">
            <h4>Doctor Dashboard</h4>
            <p>Clinical workflow</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Today’s Appointments</h5>
                    <h3>{{ \App\Models\Appointment::whereDate('appointment_date', today())->where('doctor_id', auth()->user()->doctor->id ?? 0)->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Active IPD Patients</h5>
                    <h3>{{ \App\Models\Admission::where('status', 'admitted')->where('admitting_doctor_id', auth()->user()->doctor->id ?? 0)->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Pending Lab Results</h5>
                    <h3>{{ \App\Models\LabTestOrder::where('status', 'pending')->where('doctor_id', auth()->user()->doctor->id ?? 0)->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Follow-ups</h5>
                    <h3>0</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Today's Schedule</h5>
                </div>
                <div class="card-body">
                    <!-- Placeholder for Schedule -->
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Patient</th>
                                <th>Type</th>
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
</x-app-layout>