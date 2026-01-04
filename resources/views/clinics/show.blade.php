<x-app-layout>
    <x-slot name="header">
        <h2 class="h4">Clinic Details</h2>
    </x-slot>
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">{{ $clinic->name }}</h5>
                <div class="d-flex gap-2">
                    <a href="{{ route('clinics.edit', $clinic) }}" class="btn btn-outline-primary">Edit</a>
                    <form method="POST" action="{{ route('clinics.destroy', $clinic) }}" onsubmit="return confirm('Delete this clinic?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">Delete</button>
                    </form>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="fw-semibold text-muted">Code</div>
                    <div>{{ $clinic->code }}</div>
                </div>
                <div class="col-md-3">
                    <div class="fw-semibold text-muted">Registration No.</div>
                    <div>{{ $clinic->registration_number ?: '-' }}</div>
                </div>
                <div class="col-md-3">
                    <div class="fw-semibold text-muted">Status</div>
                    <div><span class="badge bg-secondary">{{ $clinic->status }}</span></div>
                </div>
                <div class="col-md-3">
                    <div class="fw-semibold text-muted">Timezone</div>
                    <div>{{ $clinic->timezone }}</div>
                </div>
                <div class="col-md-6">
                    <div class="fw-semibold text-muted">Address</div>
                    <div>
                        {{ $clinic->address_line_1 }}<br>
                        {{ $clinic->address_line_2 }}<br>
                        {{ $clinic->city }}, {{ $clinic->state }}<br>
                        {{ $clinic->country }} {{ $clinic->postal_code }}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="fw-semibold text-muted">Phone</div>
                    <div>{{ $clinic->phone ?: '-' }}</div>
                </div>
                <div class="col-md-3">
                    <div class="fw-semibold text-muted">Email</div>
                    <div>{{ $clinic->email ?: '-' }}</div>
                </div>
                <div class="col-md-3">
                    <div class="fw-semibold text-muted">Website</div>
                    <div>{{ $clinic->website ?: '-' }}</div>
                </div>
                <div class="col-md-3">
                    <div class="fw-semibold text-muted">Currency</div>
                    <div>{{ $clinic->currency }}</div>
                </div>
                <div class="col-md-3">
                    <div class="fw-semibold text-muted">Opening Time</div>
                    <div>{{ $clinic->opening_time ?: '-' }}</div>
                </div>
                <div class="col-md-3">
                    <div class="fw-semibold text-muted">Closing Time</div>
                    <div>{{ $clinic->closing_time ?: '-' }}</div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('clinics.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</x-app-layout>
