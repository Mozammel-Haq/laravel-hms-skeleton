<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="page-title mb-0">Assign Bed</h3>
            <a href="{{ route('ipd.show', $admission->id) }}" class="btn btn-outline-secondary">Back to Admission</a>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="POST" action="{{ route('ipd.store-bed', $admission->id) }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Available Bed</label>
                                <select class="form-select" name="bed_id" required>
                                    <option value="">Select a bed</option>
                                    @foreach ($beds as $bed)
                                        <option value="{{ $bed->id }}">Bed {{ $bed->id }}
                                            ({{ $bed->status }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="d-flex">
                                <button class="btn btn-primary me-2" type="submit">Assign</button>
                                <a class="btn btn-outline-secondary" href="{{ route('ipd.index') }}">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="fw-semibold mb-2">Admission</div>
                        <div class="mb-2">
                            <div class="text-muted">Patient</div>
                            <div>{{ optional($admission->patient)->full_name ?? 'Patient' }}</div>
                        </div>
                        <div class="mb-2">
                            <div class="text-muted">Doctor</div>
                            <div>{{ optional($admission->doctor)->user->name ?? 'Doctor' }}</div>
                        </div>
                        <div class="mb-2">
                            <div class="text-muted">Admitted On</div>
                            <div>{{ $admission->created_at }}</div>
                        </div>
                        <div>
                            <div class="text-muted">Status</div>
                            <span class="badge bg-success">{{ $admission->status }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
