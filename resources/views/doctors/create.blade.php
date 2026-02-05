<x-app-layout>
    <div class="container-fluid mx-2 mt-2">
        <div class="d-flex justify-content-between align-items-center mb-3 bg-primary-subtle text-primary px-4 pt-4 pb-3 pt-3 rounded shadow-sm">
            <div>
                <h4 class="fw-bold mb-2 text-primary">Add New Doctor</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-dots mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add New Doctor</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('doctors.index') }}" class="btn btn-sm btn-outline-primary">
                <i class="ti ti-arrow-left me-1"></i> Back to List
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form action="{{ route('doctors.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-4">
                                <!-- Left Column: Photo & Basic Settings -->
                                <div class="col-md-3 border-end">
                                    <div class="text-center mb-4">
                                        <label class="form-label fw-bold mb-3">Profile Photo</label>
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="mb-3 position-relative">
                                                <img id="doctor-photo-preview" src="{{ asset('assets/img/doctors/doctor-01.jpg') }}"
                                                    class="rounded-circle border shadow-sm object-fit-cover" width="140" height="140"
                                                    alt="Preview">
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-primary mb-2" onclick="document.getElementById('profile_photo').click()">
                                                Upload Photo
                                            </button>
                                            <input type="file" id="profile_photo" name="profile_photo" class="d-none" accept="image/*"
                                                onchange="previewDoctorPhoto(event)">
                                            <div class="form-text small">Allowed: jpg, jpeg, png. Max: 2MB</div>
                                        </div>
                                    </div>

                                    <div class="card bg-light border-0 mb-3">
                                        <div class="card-body p-3">
                                            <h6 class="fw-bold text-primary mb-3">Settings</h6>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured"
                                                    {{ old('is_featured') ? 'checked' : '' }}>
                                                <label class="form-check-label small fw-semibold" for="is_featured">Feature on booking pages</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column: Form Fields -->
                                <div class="col-md-9">
                                    <!-- Account Information -->
                                    <section class="mb-4">
                                        <h5 class="text-primary fw-bold mb-3 border-bottom pb-2">Account Information</h5>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Full Name <span class="text-danger">*</span></label>
                                                <input type="text" name="name" class="form-control form-control-sm"
                                                    value="{{ old('name') }}" required placeholder="e.g. Dr. Sarah Smith">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Email <span class="text-danger">*</span></label>
                                                <input type="email" name="email" class="form-control form-control-sm"
                                                    value="{{ old('email') }}" required placeholder="e.g. sarah@hospital.com">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Phone</label>
                                                <input type="text" name="phone" class="form-control form-control-sm"
                                                    value="{{ old('phone') }}" placeholder="e.g. +1 234 567 8900">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Password <span class="text-danger">*</span></label>
                                                <input type="password" name="password" class="form-control form-control-sm" required
                                                    minlength="8" placeholder="Min. 8 characters">
                                            </div>
                                        </div>
                                    </section>

                                    <!-- Professional Profile -->
                                    <section class="mb-4">
                                        <h5 class="text-primary fw-bold mb-3 border-bottom pb-2">Professional Profile</h5>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Department <span class="text-danger">*</span></label>
                                                <select name="primary_department_id" class="form-select form-select-sm" required>
                                                    <option value="">Select Department</option>
                                                    @foreach ($departments as $department)
                                                        <option value="{{ $department->id }}"
                                                            {{ old('primary_department_id') == $department->id ? 'selected' : '' }}>
                                                            {{ $department->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Specialization <span class="text-danger">*</span></label>
                                                <select name="specialization[]" class="form-select form-select-sm select2-tags"
                                                    multiple="multiple" required>
                                                    @if (old('specialization'))
                                                        @foreach (old('specialization') as $spec)
                                                            <option value="{{ $spec }}" selected>{{ $spec }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">License No <span class="text-danger">*</span></label>
                                                <input type="text" name="license_number" class="form-control form-control-sm"
                                                    value="{{ old('license_number') }}" required placeholder="Medical License Number">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Registration No</label>
                                                <input type="text" name="registration_number" class="form-control form-control-sm"
                                                    value="{{ old('registration_number') }}" placeholder="Registration Number">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label small fw-semibold">Experience (Years)</label>
                                                <input type="number" name="experience_years" class="form-control form-control-sm"
                                                    value="{{ old('experience_years') }}" placeholder="0">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label small fw-semibold">Gender</label>
                                                <select name="gender" class="form-select form-select-sm">
                                                    <option value="">Select</option>
                                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label small fw-semibold">Date of Birth</label>
                                                <input type="date" name="date_of_birth" class="form-control form-control-sm"
                                                    value="{{ old('date_of_birth') }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label small fw-semibold">Blood Group</label>
                                                <select name="blood_group" class="form-select form-select-sm">
                                                    <option value="">Select</option>
                                                    @php
                                                        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                                                        $selectedBloodGroup = old('blood_group', $doctor->blood_group ?? null);
                                                    @endphp
                                                    @foreach ($bloodGroups as $group)
                                                        <option value="{{ $group }}" {{ $selectedBloodGroup === $group ? 'selected' : '' }}>
                                                            {{ $group }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </section>

                                    <!-- Practice Details -->
                                    <section class="mb-4">
                                        <h5 class="text-primary fw-bold mb-3 border-bottom pb-2">Practice Details</h5>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Consultation Fee</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" step="0.01" name="consultation_fee"
                                                        class="form-control form-control-sm" value="{{ old('consultation_fee') }}" placeholder="0.00">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Follow-up Fee</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" step="0.01" name="follow_up_fee"
                                                        class="form-control form-control-sm" value="{{ old('follow_up_fee') }}" placeholder="0.00">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Consultation Room</label>
                                                <input type="text" name="consultation_room_number"
                                                    class="form-control form-control-sm"
                                                    value="{{ old('consultation_room_number') }}" placeholder="e.g. 101">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Consultation Floor</label>
                                                <input type="text" name="consultation_floor" class="form-control form-control-sm"
                                                    value="{{ old('consultation_floor') }}" placeholder="e.g. 1st Floor">
                                            </div>
                                        </div>
                                    </section>

                                    <!-- Biography -->
                                    <section class="mb-4">
                                        <h5 class="text-primary fw-bold mb-3 border-bottom pb-2">Biography</h5>
                                        <div class="mb-3">
                                            <textarea name="biography" class="form-control form-control-sm" rows="4" placeholder="Brief biography of the doctor...">{{ old('biography') }}</textarea>
                                        </div>
                                    </section>

                                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                                        <a href="{{ route('doctors.index') }}" class="btn btn-light">Cancel</a>
                                        <button type="submit" class="btn btn-primary px-4">Create Doctor</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function previewDoctorPhoto(event) {
                const img = document.getElementById('doctor-photo-preview');
                if (event.target.files[0]) {
                    img.src = URL.createObjectURL(event.target.files[0]);
                }
            }

            $(document).ready(function() {
                $('.select2-tags').select2({
                    tags: true,
                    tokenSeparators: [','],
                    placeholder: "Select or type specializations",
                    allowClear: true,
                    width: '100%'
                });
            });
        </script>
    @endpush
</x-app-layout>
