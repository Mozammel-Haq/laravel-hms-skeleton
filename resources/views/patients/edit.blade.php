<x-app-layout>
    <div class="container-fluid mx-2 mt-2">
        <div class="d-flex justify-content-between align-items-center mb-3 bg-primary-subtle text-primary px-4 py-2 pt-3 rounded shadow-sm">
            <div>
                <h4 class="fw-bold mb-2 text-primary">Edit Patient</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-dots mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('patients.index') }}">Patients</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Patient</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('patients.index') }}" class="btn btn-sm btn-outline-primary">
                <i class="ti ti-arrow-left me-1"></i> Back to List
            </a>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('patients.update', $patient) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Personal Information -->
                    <div class="mb-4">
                        <h5 class="mb-3 text-primary fw-bold">Personal Information</h5>
                        <div class="row g-3">
                            <!-- Profile Photo -->
                            <div class="col-md-2 d-flex flex-column align-items-center justify-content-center">
                                <div class="mb-2 position-relative">
                                    <img id="patient-photo-preview"
                                         src="{{ $patient->profile_photo ? asset($patient->profile_photo) : asset('assets/img/default-avatar.png') }}"
                                         alt="Preview"
                                         class="rounded-circle border shadow-sm"
                                         style="width:100px;height:100px;object-fit:cover;{{ $patient->profile_photo ? '' : 'display:none;' }}">
                                    @if(!$patient->profile_photo)
                                    <div id="photo-placeholder" class="rounded-circle border bg-light d-flex align-items-center justify-content-center text-muted" style="width:100px;height:100px;">
                                        <i class="ti ti-camera fs-2"></i>
                                    </div>
                                    @endif
                                </div>
                                <input type="file" id="profile_photo" name="profile_photo" class="d-none" accept="image/*">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="document.getElementById('profile_photo').click()">
                                    Change Photo
                                </button>
                                @error('profile_photo')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-10">
                                <div class="row g-3">
                                    <!-- Full Name -->
                                    <div class="col-md-4">
                                        <label for="name" class="form-label small fw-semibold">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', $patient->name) }}" required>
                                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- Date of Birth -->
                                    <div class="col-md-3">
                                        <label for="date_of_birth" class="form-label small fw-semibold">Date of Birth <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control form-control-sm @error('date_of_birth') is-invalid @enderror"
                                            id="date_of_birth" name="date_of_birth"
                                            value="{{ old('date_of_birth', $patient->date_of_birth) }}" required>
                                        @error('date_of_birth') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- Gender -->
                                    <div class="col-md-2">
                                        <label for="gender" class="form-label small fw-semibold">Gender <span class="text-danger">*</span></label>
                                        <select id="gender" name="gender" class="form-select form-select-sm @error('gender') is-invalid @enderror" required>
                                            <option value="">Select</option>
                                            <option value="male" {{ old('gender', $patient->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender', $patient->gender) === 'female' ? 'selected' : '' }}>Female</option>
                                            <option value="other" {{ old('gender', $patient->gender) === 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- Blood Group -->
                                    <div class="col-md-3">
                                        <label for="blood_group" class="form-label small fw-semibold">Blood Group</label>
                                        <select id="blood_group" name="blood_group" class="form-select form-select-sm @error('blood_group') is-invalid @enderror">
                                            <option value="">Select</option>
                                            @foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $group)
                                                <option value="{{ $group }}" {{ old('blood_group', $patient->blood_group) === $group ? 'selected' : '' }}>{{ $group }}</option>
                                            @endforeach
                                        </select>
                                        @error('blood_group') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="text-muted opacity-25">

                    <!-- Contact Information -->
                    <div class="mb-4">
                        <h5 class="mb-3 text-primary fw-bold">Contact Information</h5>
                        <div class="row g-3">
                            <!-- Phone -->
                            <div class="col-md-4">
                                <label for="phone" class="form-label small fw-semibold">Phone Number <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="ti ti-phone"></i></span>
                                    <input type="text" class="form-control form-control-sm @error('phone') is-invalid @enderror"
                                        id="phone" name="phone" value="{{ old('phone', $patient->phone) }}" required>
                                </div>
                                @error('phone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-4">
                                <label for="email" class="form-label small fw-semibold">Email</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="ti ti-mail"></i></span>
                                    <input type="email" class="form-control form-control-sm @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email', $patient->email) }}">
                                </div>
                                @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            <!-- Address -->
                            <div class="col-md-12">
                                <label for="address" class="form-label small fw-semibold">Address <span class="text-danger">*</span></label>
                                <textarea id="address" name="address" rows="2" class="form-control form-control-sm @error('address') is-invalid @enderror"
                                    required>{{ old('address', $patient->address) }}</textarea>
                                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <!-- Emergency Contact -->
                            <div class="col-md-6">
                                <label for="emergency_contact_name" class="form-label small fw-semibold">Emergency Contact Name</label>
                                <input type="text" class="form-control form-control-sm @error('emergency_contact_name') is-invalid @enderror"
                                    id="emergency_contact_name" name="emergency_contact_name"
                                    value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}">
                                @error('emergency_contact_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="emergency_contact_phone" class="form-label small fw-semibold">Emergency Contact Phone</label>
                                <input type="text" class="form-control form-control-sm @error('emergency_contact_phone') is-invalid @enderror"
                                    id="emergency_contact_phone" name="emergency_contact_phone"
                                    value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone) }}">
                                @error('emergency_contact_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <hr class="text-muted opacity-25">

                    <!-- Identity Information -->
                    <div class="mb-4">
                        <h5 class="mb-3 text-primary fw-bold">Identity Information</h5>
                        <p class="text-muted small mb-3">Provide at least one identification number.</p>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="nid_number" class="form-label small fw-semibold">NID Number</label>
                                <input type="text" class="form-control form-control-sm @error('nid_number') is-invalid @enderror"
                                    id="nid_number" name="nid_number"
                                    value="{{ old('nid_number', $patient->nid_number) }}">
                                @error('nid_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="birth_certificate_number" class="form-label small fw-semibold">Birth Certificate Number</label>
                                <input type="text" class="form-control form-control-sm @error('birth_certificate_number') is-invalid @enderror"
                                    id="birth_certificate_number" name="birth_certificate_number"
                                    value="{{ old('birth_certificate_number', $patient->birth_certificate_number) }}">
                                @error('birth_certificate_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="passport_number" class="form-label small fw-semibold">Passport Number</label>
                                <input type="text" class="form-control form-control-sm @error('passport_number') is-invalid @enderror"
                                    id="passport_number" name="passport_number"
                                    value="{{ old('passport_number', $patient->passport_number) }}">
                                @error('passport_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('patients.index') }}" class="btn btn-light">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            Update Patient
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('profile_photo');
            const preview = document.getElementById('patient-photo-preview');
            const placeholder = document.getElementById('photo-placeholder');

            if (!input || !preview) return;

            input.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (!file) {
                    if (placeholder) {
                        preview.style.display = 'none';
                        placeholder.style.display = 'flex';
                    }
                    return;
                }
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
                if (placeholder) placeholder.style.display = 'none';
            });
        });
    </script>
    @endpush
</x-app-layout>
