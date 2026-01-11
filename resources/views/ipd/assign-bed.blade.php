<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">Assign Bed</h4>
                <p class="text-muted mb-0">Assign a bed to the admitted patient</p>
            </div>
            <a href="{{ route('ipd.show', $admission->id) }}" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-1"></i> Back to Admission
            </a>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form method="POST" action="{{ route('ipd.store-bed', $admission->id) }}">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Select Available Bed</label>
                                <select class="form-select" name="bed_id" required size="10">
                                    <option value="" disabled selected>Select a bed...</option>
                                    @foreach ($beds as $bed)
                                        <option value="{{ $bed->id }}">
                                            Bed {{ $bed->bed_number }} - Room {{ $bed->room->room_number ?? 'N/A' }} 
                                            ({{ $bed->room->ward->name ?? 'N/A' }}) - {{ ucfirst($bed->room->room_type ?? '') }}
                                        </option>
                                    @endforeach
                                </select>
                                @if($beds->isEmpty())
                                    <div class="form-text text-danger mt-2">
                                        No available beds found. Please discharge patients or add new beds.
                                    </div>
                                @endif
                            </div>
                            <div class="d-flex justify-content-end gap-2">
                                <a class="btn btn-light" href="{{ route('ipd.show', $admission) }}">Cancel</a>
                                <button class="btn btn-primary" type="submit" {{ $beds->isEmpty() ? 'disabled' : '' }}>
                                    <i class="ti ti-check me-1"></i> Assign Bed
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent">
                        <h5 class="card-title mb-0">Patient Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar avatar-md me-3">
                                <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                    {{ substr($admission->patient->name, 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $admission->patient->name }}</h6>
                                <div class="text-muted small">{{ $admission->patient->patient_code }}</div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="text-muted small text-uppercase fw-bold mb-1">Attending Doctor</div>
                            <div>Dr. {{ $admission->doctor->user->name }}</div>
                        </div>

                        <div class="mb-3">
                            <div class="text-muted small text-uppercase fw-bold mb-1">Admission Date</div>
                            <div>{{ $admission->created_at->format('d M Y, h:i A') }}</div>
                        </div>

                        <div>
                            <div class="text-muted small text-uppercase fw-bold mb-1">Current Status</div>
                            <span class="badge bg-{{ $admission->status === 'admitted' ? 'success' : 'secondary' }}">
                                {{ ucfirst($admission->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
