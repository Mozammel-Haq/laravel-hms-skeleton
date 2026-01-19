<x-app-layout>
    <div class="container-fluid mx-2">
        <div class="card mb-3 mt-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="page-title mb-0">Reports</h3>
                    <a href="{{ route('reports.financial') }}" class="btn btn-outline-primary">Financial</a>
                </div>
                <hr>
            </div>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <a href="{{ route('reports.summary') }}" class="text-decoration-none">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="fw-semibold mb-1">OPD/IPD Summary</div>
                            <div class="text-muted">Overview metrics and recent admissions</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('reports.financial') }}" class="text-decoration-none">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="fw-semibold mb-1">Financial</div>
                            <div class="text-muted">Revenue and paid totals by date range</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('reports.demographics') }}" class="text-decoration-none">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="fw-semibold mb-1">Patient Demographics</div>
                            <div class="text-muted">Gender distribution and more</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('reports.doctor_performance') }}" class="text-decoration-none">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="fw-semibold mb-1">Doctor Performance</div>
                            <div class="text-muted">Top performers by consultations and admissions</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
