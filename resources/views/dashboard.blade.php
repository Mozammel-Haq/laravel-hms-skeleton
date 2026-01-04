<x-app-layout>
    <div class="page-header d-flex justify-content-between align-items-center">
        <div class="page-title">
            <h4>Dashboard</h4>
            <p>Welcome, {{ auth()->user()->name }}</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5>Welcome to Trustcare Hospital Management System</h5>
                    <p>Select an option from the sidebar to get started.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
