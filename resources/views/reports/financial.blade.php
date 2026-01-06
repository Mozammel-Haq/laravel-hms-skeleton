<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="page-title mb-0">Financial Report</h3>
            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">Reports Hub</a>
        </div>
        <form method="get" action="{{ route('reports.financial') }}" class="card mb-3">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ \Illuminate\Support\Carbon::parse($startDate)->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ \Illuminate\Support\Carbon::parse($endDate)->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">Apply</button>
                    </div>
                </div>
            </div>
        </form>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-muted">Total Revenue</div>
                        <div class="display-6">{{ number_format($revenue, 2) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-muted">Paid Amount</div>
                        <div class="display-6 text-success">{{ number_format($paid, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
