<x-app-layout>
    <div class="container-fluid mx-2 mt-2">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 py-2 rounded shadow-sm mb-3">
            <div>
                <h5 class="fw-bold mb-1 text-primary">Clinic Details</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-dots mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $clinic->name }}</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('clinics.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="ti ti-arrow-left me-1"></i> Back
                </a>
                @can('update', $clinic)
                    <a href="{{ route('clinics.edit', $clinic) }}" class="btn btn-sm btn-primary">
                        <i class="ti ti-edit me-1"></i> Edit
                    </a>
                @endcan
                @can('delete', $clinic)
                    <form method="POST" action="{{ route('clinics.destroy', $clinic) }}"
                        onsubmit="return confirm('Delete this clinic?')" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="ti ti-trash me-1"></i> Delete
                        </button>
                    </form>
                @endcan
            </div>
        </div>

        <div class="row g-3">
            <!-- Left Column: Key Info -->
            <div class="col-lg-4">
                <div class="card border border-secondary-subtle shadow-sm mb-4">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            @if ($clinic->logo_path)
                                <img src="{{ asset("/") }}/{{ Storage::url($clinic->logo_path) }}" class="rounded me-3 border" width="80"
                                height="80" style="object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-primary-subtle d-inline-flex align-items-center justify-content-center text-primary"
                                    style="width: 120px; height: 120px;">
                                    <i class="ti ti-building-hospital fs-1"></i>
                                </div>
                            @endif
                        </div>
                        <h4 class="mb-1 fw-bold text-dark">{{ $clinic->name }}</h4>
                        <p class="text-muted mb-3">{{ $clinic->code }}</p>

                        <div class="d-flex justify-content-center gap-2 mb-4">
                            @php
                                $status = $clinic->status;
                                $statusColor = match ($status) {
                                    'active' => 'success',
                                    'inactive' => 'warning',
                                    default => 'secondary',
                                };
                            @endphp
                            <span class="badge bg-{{ $statusColor }}-subtle text-{{ $statusColor }} border border-{{ $statusColor }}-subtle rounded-pill px-3">
                                {{ ucfirst($status) }}
                            </span>
                        </div>

                        <hr class="my-3 border-secondary-subtle">

                        <div class="text-start">
                            <div class="mb-3">
                                <label class="text-muted small text-uppercase fw-bold mb-1">Registration</label>
                                <div class="d-flex align-items-center">
                                    <i class="ti ti-id-badge-2 text-muted me-2"></i>
                                    <span class="text-dark">{{ $clinic->registration_number ?: 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small text-uppercase fw-bold mb-1">Contact</label>
                                <div class="d-flex align-items-center mb-1">
                                    <i class="ti ti-phone text-muted me-2"></i>
                                    <span class="text-dark">{{ $clinic->phone ?: 'N/A' }}</span>
                                </div>
                                <div class="d-flex align-items-center mb-1">
                                    <i class="ti ti-mail text-muted me-2"></i>
                                    <span class="text-dark text-break">{{ $clinic->email ?: 'N/A' }}</span>
                                </div>
                                @if($clinic->website)
                                    <div class="d-flex align-items-center">
                                        <i class="ti ti-world text-muted me-2"></i>
                                        <a href="{{ $clinic->website }}" target="_blank" class="text-primary text-decoration-none">{{ $clinic->website }}</a>
                                    </div>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small text-uppercase fw-bold mb-1">Address</label>
                                <div class="d-flex align-items-start">
                                    <i class="ti ti-map-pin text-muted me-2 mt-1"></i>
                                    <span class="text-dark">
                                        {{ $clinic->address_line_1 }}
                                        @if($clinic->address_line_2) <br>{{ $clinic->address_line_2 }} @endif
                                        <br>{{ $clinic->city }}, {{ $clinic->state }}
                                        <br>{{ $clinic->country }} - {{ $clinic->postal_code }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Operating Info -->
                <div class="card border border-secondary-subtle shadow-sm mb-4">
                    <div class="card-header bg-transparent border-bottom py-3">
                        <h6 class="card-title mb-0 fw-bold text-dark">
                            <i class="ti ti-clock me-2 text-info"></i> Operations
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Opening Time</span>
                            <span class="fw-medium text-dark">{{ $clinic->opening_time ?: 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Closing Time</span>
                            <span class="fw-medium text-dark">{{ $clinic->closing_time ?: 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Timezone</span>
                            <span class="fw-medium text-dark">{{ $clinic->timezone }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Currency</span>
                            <span class="fw-medium text-dark">{{ $clinic->currency }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Details, Services, Gallery -->
            <div class="col-lg-8">
                <!-- About -->
                <div class="card border border-secondary-subtle shadow-sm mb-4">
                    <div class="card-header bg-transparent border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="ti ti-info-circle me-2 text-primary"></i> About Clinic
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($clinic->about)
                            <p class="text-secondary mb-0">{!! nl2br(e($clinic->about)) !!}</p>
                        @else
                            <p class="text-muted fst-italic mb-0">No description provided for this clinic.</p>
                        @endif
                    </div>
                </div>

                <!-- Services -->
                <div class="card border border-secondary-subtle shadow-sm mb-4">
                    <div class="card-header bg-transparent border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="ti ti-stethoscope me-2 text-success"></i> Services
                        </h5>
                    </div>
                    <div class="card-body">
                        @php $services = $clinic->services ?? []; @endphp
                        @if (!empty($services))
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($services as $service)
                                    <span class="badge bg-white text-dark border border-secondary-subtle p-2 d-flex align-items-center">
                                        <i class="ti ti-check text-success me-2"></i> {{ $service }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted fst-italic mb-0">No services listed.</p>
                        @endif
                    </div>
                </div>

                <!-- Gallery -->
                @if ($clinic->images->count() > 0)
                    <div class="card border border-secondary-subtle shadow-sm mb-4">
                        <div class="card-header bg-transparent border-bottom py-3">
                            <h5 class="card-title mb-0 fw-bold text-dark">
                                <i class="ti ti-photo me-2 text-warning"></i> Gallery
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @foreach ($clinic->images as $image)
                                    <div class="col-md-4 col-sm-6">
                                        <a href="{{ asset("/") }}/{{ Storage::url($image->image_path) }}" target="_blank">
                                    <img src="{{ asset("/") }}/{{ Storage::url($image->image_path) }}"
                                        class="img-fluid rounded shadow-sm w-100 border"
                                        style="height: 200px; object-fit: cover;">
                                </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
