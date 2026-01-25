<x-app-layout>

    <div class="py-4">
        <div class="container">
            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <!-- Title -->
                    <h2 class="fw-semibold fs-4 text-dark mb-3">
                        Add New Patient
                    </h2>
                    <hr class="mb-4">

                    <form method="POST" action="{{ route('patients.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-3">

                            <!-- Profile Photo -->
                            <div class="col-12">
                                <label class="form-label">Profile Photo (Optional)</label>
                                <div class="d-flex align-items-center gap-3">
                                    <img id="patient-photo-preview"
                                         src=""
                                         alt="Preview"
                                         class="rounded-circle border"
                                         style="width:80px;height:80px;object-fit:cover;display:none;">
                                    <input type="file"
                                           id="profile_photo"
                                           name="profile_photo"
                                           class="form-control @error('profile_photo') is-invalid @enderror"
                                           accept="image/*">
                                    @error('profile_photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Full Name -->
                            <div class="col-12">
                                <label class="form-label">Full Name</label>
                                <input type="text"
                                       name="name"
                                       value="{{ old('name') }}"
                                       class="form-control @error('name') is-invalid @enderror"
                                       required autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date of Birth -->
                            <div class="col-md-6">
                                <label class="form-label">Date of Birth</label>
                                <input type="date"
                                       name="date_of_birth"
                                       value="{{ old('date_of_birth') }}"
                                       class="form-control @error('date_of_birth') is-invalid @enderror"
                                       required>
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Gender -->
                            <div class="col-md-6">
                                <label class="form-label">Gender</label>
                                <select name="gender"
                                        class="form-select @error('gender') is-invalid @enderror"
                                        required>
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6">
                                <label class="form-label">Phone Number</label>
                                <input type="text"
                                       name="phone"
                                       value="{{ old('phone') }}"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label class="form-label">Email (Optional)</label>
                                <input type="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       class="form-control @error('email') is-invalid @enderror">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Blood Group -->
                            <div class="col-md-6">
                                <label class="form-label">Blood Group (Optional)</label>
                                <select name="blood_group"
                                        class="form-select @error('blood_group') is-invalid @enderror">
                                    <option value="">Select Blood Group</option>
                                    @foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $group)
                                        <option value="{{ $group }}" {{ old('blood_group') === $group ? 'selected' : '' }}>
                                            {{ $group }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('blood_group')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Address -->
                            <div class="col-12">
                                <label class="form-label">Address</label>
                                <textarea name="address"
                                          rows="3"
                                          class="form-control @error('address') is-invalid @enderror"
                                          required>{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Emergency Contact -->
                            <div class="col-md-6">
                                <label class="form-label">Emergency Contact Name</label>
                                <input type="text"
                                       name="emergency_contact_name"
                                       value="{{ old('emergency_contact_name') }}"
                                       class="form-control @error('emergency_contact_name') is-invalid @enderror">
                                @error('emergency_contact_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Emergency Contact Phone</label>
                                <input type="text"
                                       name="emergency_contact_phone"
                                       value="{{ old('emergency_contact_phone') }}"
                                       class="form-control @error('emergency_contact_phone') is-invalid @enderror">
                                @error('emergency_contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <hr>
                                <h6 class="fw-semibold">Identity Information</h6>
                                <p class="text-muted mb-2">Provide NID if available, otherwise Birth Certificate or Passport.</p>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">NID Number</label>
                                <input type="text"
                                       name="nid_number"
                                       value="{{ old('nid_number') }}"
                                       class="form-control @error('nid_number') is-invalid @enderror">
                                @error('nid_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Birth Certificate Number</label>
                                <input type="text"
                                       name="birth_certificate_number"
                                       value="{{ old('birth_certificate_number') }}"
                                       class="form-control @error('birth_certificate_number') is-invalid @enderror">
                                @error('birth_certificate_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Passport Number</label>
                                <input type="text"
                                       name="passport_number"
                                       value="{{ old('passport_number') }}"
                                       class="form-control @error('passport_number') is-invalid @enderror">
                                @error('passport_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('patients.index') }}" class="btn btn-light me-2">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Save Patient
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const input = document.getElementById('profile_photo');
                const preview = document.getElementById('patient-photo-preview');

                if (!input || !preview) return;

                input.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if (!file) {
                        preview.style.display = 'none';
                        return;
                    }
                    preview.src = URL.createObjectURL(file);
                    preview.style.display = 'block';
                });
            });
        </script>
    @endpush

</x-app-layout>
