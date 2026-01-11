<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="page-title mb-0">Super Admin Dashboard</h3>
            <div class="text-muted">{{ now()->format('l, d M Y') }}</div>
        </div>

        <div class="alert alert-info">
            <i class="ti ti-info-circle me-2"></i>
            Welcome to the Super Admin Dashboard. Use the sidebar to manage clinics, subscriptions, and system-wide settings.
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center p-5">
                        <div class="avatar avatar-xl bg-primary-subtle text-primary rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                            <i class="ti ti-building-hospital fs-1"></i>
                        </div>
                        <h4>Manage Clinics</h4>
                        <p class="text-muted">View and manage all registered clinics in the system.</p>
                        <!-- Assuming a route exists, otherwise just a placeholder -->
                        <a href="#" class="btn btn-primary">Go to Clinics</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center p-5">
                        <div class="avatar avatar-xl bg-success-subtle text-success rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                            <i class="ti ti-users fs-1"></i>
                        </div>
                        <h4>System Users</h4>
                        <p class="text-muted">Manage system administrators and user roles.</p>
                        <a href="#" class="btn btn-outline-success">Manage Users</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center p-5">
                        <div class="avatar avatar-xl bg-warning-subtle text-warning rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                            <i class="ti ti-settings fs-1"></i>
                        </div>
                        <h4>Settings</h4>
                        <p class="text-muted">Configure global application settings.</p>
                        <a href="#" class="btn btn-outline-warning">System Settings</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
