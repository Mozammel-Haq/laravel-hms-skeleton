<x-app-layout>
    <div class="page-header d-flex justify-content-between align-items-center">
        <div class="page-title">
            <h4>Super Admin Dashboard</h4>
            <p>Cross-clinic governance and analytics</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Total Clinics</h5>
                    <h3>{{ \App\Models\Clinic::count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Global Reports</h5>
                    <p>View system-wide analytics</p>
                    <a href="#" class="btn btn-primary btn-sm">View Reports</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>System Health</h5>
                    <span class="badge bg-success">Operational</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
