<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="page-title mb-0">Record Vitals</h3>
            <a href="{{ route('vitals.history') }}" class="btn btn-outline-secondary">View History</a>
        </div>
        <div class="card">
            <div class="card-body">
                <form class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Patient</label>
                        <select class="form-select" required>
                            <option value="">Select patient</option>
                            @foreach ($patients as $p)
                                <option value="{{ $p->id }}">{{ $p->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Temperature (°C)</label>
                        <input type="number" step="0.1" class="form-control" placeholder="36.6">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Pulse (bpm)</label>
                        <input type="number" class="form-control" placeholder="72">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">BP (mmHg)</label>
                        <input type="text" class="form-control" placeholder="120/80">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Respiratory Rate</label>
                        <input type="number" class="form-control" placeholder="16">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" rows="3" placeholder="Observations"></textarea>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary" type="button">Save</button>
                        <button class="btn btn-outline-secondary" type="reset">Clear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
