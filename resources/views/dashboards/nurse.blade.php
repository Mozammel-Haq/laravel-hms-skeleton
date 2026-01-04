<x-app-layout>
    <div class="page-header d-flex justify-content-between align-items-center">
        <div class="page-title">
            <h4>Nurse Dashboard</h4>
            <p>IPD & Patient Care</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Admitted Patients</h5>
                    <h3>{{ \App\Models\Admission::where('status', 'Admitted')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Available Beds</h5>
                    <h3>0</h3> <!-- Need Bed model logic -->
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Pending Vitals</h5>
                    <h3>0</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Ward Status</h5>
                </div>
                <div class="card-body">
                    <!-- Placeholder for Ward View -->
                    <p>Visual representation of beds/wards.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>