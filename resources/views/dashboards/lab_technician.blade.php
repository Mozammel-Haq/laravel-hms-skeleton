<x-app-layout>
    <div class="page-header d-flex justify-content-between align-items-center">
        <div class="page-title">
            <h4>Lab Technician Dashboard</h4>
            <p>Laboratory Operations</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Pending Orders</h5>
                    <h3>{{ \App\Models\LabTestOrder::where('status', 'Pending')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>In Progress</h5>
                    <h3>{{ \App\Models\LabTestOrder::where('status', 'In Progress')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Completed Today</h5>
                    <h3>{{ \App\Models\LabTestOrder::where('status', 'Completed')->whereDate('updated_at', today())->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Test Queue</h5>
                </div>
                <div class="card-body">
                    <!-- Placeholder for Test Queue -->
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Patient</th>
                                <th>Test</th>
                                <th>Priority</th>
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