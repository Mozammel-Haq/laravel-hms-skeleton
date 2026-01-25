<x-app-layout>
    <div class="content pb-0">
        <div class="card mt-2">
            <div class="card-body">
                <h2 class="h4">Clinic Details</h2>
                <hr>
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center">
                        @if ($clinic->logo_path)
                            <img src="{{ Storage::url($clinic->logo_path) }}" class="rounded me-3 border" width="80"
                                height="80" style="object-fit: cover;">
                        @else
                            <div class="rounded me-3 bg-light border d-flex align-items-center justify-content-center text-secondary"
                                style="width: 80px; height: 80px;">
                                <i class="ti ti-building-hospital fs-1"></i>
                            </div>
                        @endif
                        <div>
                            <h5 class="card-title mb-1 fs-3">{{ $clinic->name }}</h5>
                            <div class="text-muted">{{ $clinic->city }}, {{ $clinic->country }}</div>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('clinics.edit', $clinic) }}" class="btn btn-outline-primary">Edit</a>
                        <form method="POST" action="{{ route('clinics.destroy', $clinic) }}"
                            onsubmit="return confirm('Delete this clinic?')">
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
                        <div>
                            @php
                                $status = $clinic->status;
                                $color = match ($status) {
                                    'active' => 'success',
                                    'inactive' => 'warning',
                                    default => 'secondary',
                                };
                            @endphp
                            <span class="badge bg-{{ $color }}">{{ ucfirst($status) }}</span>
                        </div>
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

                @if ($clinic->images->count() > 0)
                    <hr class="my-4">
                    <h5 class="mb-3">Gallery</h5>
                    <div class="row g-3">
                        @foreach ($clinic->images as $image)
                            <div class="col-md-3 col-6">
                                <a href="{{ Storage::url($image->image_path) }}" target="_blank">
                                    <img src="{{ Storage::url($image->image_path) }}"
                                        class="img-fluid rounded shadow-sm w-100 border"
                                        style="height: 200px; object-fit: cover;">
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="card-footer">
                <a href="{{ route('clinics.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </div>
</x-app-layout>
