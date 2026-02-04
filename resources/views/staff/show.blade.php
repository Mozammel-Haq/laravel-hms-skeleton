<x-app-layout>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row align-items-center mb-2 bg-primary-subtle text-primary px-4 py-3 pt-3">
            <div class="col">
                <h4 class="mb-1 fw-bold text-dark">Staff Profile</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('staff.index') }}" class="text-decoration-none">Staff</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $staff->name }}</li>
                    </ol>
                </nav>
            </div>
            <div class="col-auto d-flex gap-2">
                <a href="{{ route('staff.index') }}" class="btn btn-outline-primary">
                    <i class="ti ti-arrow-left me-1"></i> Back
                </a>
                @can('update', $staff)
                    <a href="{{ route('staff.edit', $staff->id) }}" class="btn btn-primary">
                        <i class="ti ti-edit me-1"></i> Edit Profile
                    </a>
                @endcan
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border border-secondary-subtle shadow-sm">
                    <div class="card-body text-center p-5">
                        <!-- Profile Image -->
                        <div class="position-relative d-inline-block mb-4">
                            <div class="rounded-circle border border-3 border-white shadow-sm overflow-hidden" style="width: 140px; height: 140px;">
                                @if($staff->profile_photo_path)
                                    <img src="{{ asset('storage/' . $staff->profile_photo_path) }}"
                                         alt="{{ $staff->name }}"
                                         class="w-100 h-100 object-fit-cover">
                                @else
                                    <div class="w-100 h-100 bg-primary-subtle d-flex align-items-center justify-content-center text-primary fs-1 fw-bold">
                                        {{ strtoupper(substr($staff->name, 0, 2)) }}
                                    </div>
                                @endif
                            </div>
                            <span class="position-absolute bottom-0 end-0 badge rounded-pill bg-{{ $staff->status === 'active' ? 'success' : 'secondary' }} border border-2 border-white">
                                {{ ucfirst($staff->status ?? 'Active') }}
                            </span>
                        </div>

                        <!-- Name & Role -->
                        <h3 class="mb-1 fw-bold text-dark">{{ $staff->name }}</h3>
                        <div class="mb-4">
                            @foreach($staff->roles as $role)
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2 mx-1">
                                    {{ $role->name }}
                                </span>
                            @endforeach
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <hr class="border-secondary-subtle mb-4">

                                <div class="row g-4 text-start">
                                    <div class="col-sm-6">
                                        <div class="d-flex align-items-start">
                                            <div class="bg-light rounded-circle p-2 me-3 text-primary">
                                                <i class="ti ti-mail fs-5"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small text-uppercase fw-bold d-block mb-1">Email Address</label>
                                                <span class="text-dark fw-medium text-break">{{ $staff->email }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex align-items-start">
                                            <div class="bg-light rounded-circle p-2 me-3 text-primary">
                                                <i class="ti ti-building-hospital fs-5"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small text-uppercase fw-bold d-block mb-1">Clinic</label>
                                                <span class="text-dark fw-medium">{{ optional($staff->clinic)->name ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex align-items-start">
                                            <div class="bg-light rounded-circle p-2 me-3 text-primary">
                                                <i class="ti ti-calendar fs-5"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small text-uppercase fw-bold d-block mb-1">Joined On</label>
                                                <span class="text-dark fw-medium">{{ $staff->created_at->format('d M, Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex align-items-start">
                                            <div class="bg-light rounded-circle p-2 me-3 text-primary">
                                                <i class="ti ti-clock fs-5"></i>
                                            </div>
                                            <div>
                                                <label class="text-muted small text-uppercase fw-bold d-block mb-1">Last Updated</label>
                                                <span class="text-dark fw-medium">{{ $staff->updated_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
