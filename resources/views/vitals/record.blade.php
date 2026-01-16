<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="page-title mb-0">Record Vitals</h3>
            <a href="{{ route('vitals.history') }}" class="btn btn-outline-secondary">View History</a>
        </div>
        <div class="card">
            <div class="card-body">
                <form class="row g-3" method="POST" action="{{ route('vitals.store') }}">
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label">Patient</label>
                        <select class="form-select" name="patient_id" required>
                            <option value="">Select patient</option>
                            @foreach ($patients as $p)
                                <option value="{{ $p->id }}" @selected(old('patient_id') == $p->id)>
                                    {{ $p->name ?? ($p->full_name ?? 'Patient') }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if (isset($visit) && $visit)
                        <input type="hidden" name="visit_id" value="{{ $visit->id }}">
                    @endif
                    @if (request()->has('admission_id'))
                        <input type="hidden" name="admission_id" value="{{ request('admission_id') }}">
                    @endif
                    <div class="col-md-3">
                        <label class="form-label">Temperature (°C)</label>
                        <input type="number" step="0.1" class="form-control" name="temperature"
                            value="{{ old('temperature') }}" placeholder="36.6">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Pulse (bpm)</label>
                        <input type="number" class="form-control" name="heart_rate" value="{{ old('heart_rate') }}"
                            placeholder="72">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">BP (mmHg)</label>
                        <input type="text" class="form-control" name="blood_pressure"
                            value="{{ old('blood_pressure') }}" placeholder="120/80">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Respiratory Rate</label>
                        <input type="number" class="form-control" name="respiratory_rate"
                            value="{{ old('respiratory_rate') }}" placeholder="16">
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary" type="submit">Save</button>
                        <button class="btn btn-outline-secondary" type="reset">Clear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
