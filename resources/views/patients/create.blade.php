<x-app-layout>

    <div class="container-fluid mx-2 mt-2">
        <div class="card border-0 shadow-sm">

                <div class="card-body p-3">
                    <div id="global-search-alert" class="alert alert-info border-0 shadow-sm mb-3 d-none">
                        <div class="d-flex align-items-center">
                            <i class="ti ti-info-circle fs-4 me-2"></i>
                            <div>
                                <span id="alert-message"></span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3 bg-primary-subtle text-primary px-4 pt-4 pb-3 pt-3 rounded shadow-sm">
                        <div>
                            <h4 class="fw-bold mb-2 text-primary">Add New Patient</h4>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-dots mb-0">
                                    <li class="breadcrumb-item"><a href="{{ route('patients.index') }}">Patients</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Add New Patient</li>
                                </ol>
                            </nav>
                        </div>
                        <a href="{{ route('patients.index') }}" class="btn btn-sm btn-outline-primary">
                            <i class="ti ti-arrow-left me-1"></i> Back to List
                        </a>
                    </div>
                    <hr>
                    <form method="POST" action="{{ route('patients.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Personal Information -->
                        <div class="mb-4">
                            <h5 class="mb-3 text-primary fw-bold">Personal Information</h5>
                            <div class="row g-3">
                                <!-- Profile Photo -->
                                <div class="col-md-2 d-flex flex-column align-items-center justify-content-center">
                                    <div class="mb-2">
                                        <img id="patient-photo-preview"
                                             src="{{ asset('assets/img/default-avatar.png') }}"
                                             alt="Preview"
                                             class="rounded-circle border shadow-sm"
                                             style="width:100px;height:100px;object-fit:cover;display:none;">
                                        <div id="photo-placeholder" class="rounded-circle border bg-light d-flex align-items-center justify-content-center text-muted" style="width:100px;height:100px;">
                                            <i class="ti ti-camera fs-2"></i>
                                        </div>
                                    </div>
                                    <input type="file" id="profile_photo" name="profile_photo" class="d-none" accept="image/*">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="document.getElementById('profile_photo').click()">
                                        Upload Photo
                                    </button>
                                    @error('profile_photo')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-10">
                                    <div class="row g-3">
                                        <!-- Full Name -->
                                        <div class="col-md-4">
                                            <label class="form-label small text-muted">Full Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" value="{{ old('name') }}"
                                                   class="form-control form-control-sm @error('name') is-invalid @enderror"
                                                   placeholder="e.g. John Doe" required autofocus>
                                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>

                                        <!-- Date of Birth -->
                                        <div class="col-md-3">
                                            <label class="form-label small fw-semibold">Date of Birth <span class="text-danger">*</span></label>
                                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"
                                                   class="form-control form-control-sm @error('date_of_birth') is-invalid @enderror" required>
                                            @error('date_of_birth') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>

                                        <!-- Gender -->
                                        <div class="col-md-2">
                                            <label class="form-label small fw-semibold">Gender <span class="text-danger">*</span></label>
                                            <select name="gender" class="form-select form-select-sm @error('gender') is-invalid @enderror" required>
                                                <option value="">Select</option>
                                                <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                                                <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                                                <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>

                                        <!-- Blood Group -->
                                        <div class="col-md-3">
                                            <label class="form-label small fw-semibold">Blood Group</label>
                                            <select name="blood_group" class="form-select form-select-sm @error('blood_group') is-invalid @enderror">
                                                <option value="">Select</option>
                                                @foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $group)
                                                    <option value="{{ $group }}" {{ old('blood_group') === $group ? 'selected' : '' }}>{{ $group }}</option>
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
                                    <label class="form-label small fw-semibold">Phone Number <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text"><i class="ti ti-phone"></i></span>
                                        <input type="text" name="phone" value="{{ request('phone', old('phone')) }}"
                                               class="form-control form-control-sm @error('phone') is-invalid @enderror"
                                               placeholder="e.g. 01700000000" required>
                                    </div>
                                    @error('phone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                                <!-- Email -->
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">Email</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text"><i class="ti ti-mail"></i></span>
                                        <input type="email" name="email" value="{{ old('email') }}"
                                               class="form-control form-control-sm @error('email') is-invalid @enderror"
                                               placeholder="e.g. email@example.com">
                                    </div>
                                    @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                                <!-- Address -->
                                <div class="col-md-12">
                                    <label class="form-label small fw-semibold">Address <span class="text-danger">*</span></label>
                                    <textarea name="address" rows="2" class="form-control form-control-sm @error('address') is-invalid @enderror" required>{{ old('address') }}</textarea>
                                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <!-- Emergency Contact -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Emergency Contact Name</label>
                                    <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}"
                                           class="form-control form-control-sm @error('emergency_contact_name') is-invalid @enderror">
                                    @error('emergency_contact_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Emergency Contact Phone</label>
                                    <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}"
                                           class="form-control form-control-sm @error('emergency_contact_phone') is-invalid @enderror">
                                    @error('emergency_contact_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="text-muted opacity-25">

                        <!-- Identity Information -->
                        <div class="mb-4">
                            <h5 class="mb-3 text-primary fw-bold">Identity Information</h5>
                            <p class="text-muted small mb-3">Provide at least one form of identification if available.</p>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">NID Number</label>
                                    <input type="text" name="nid_number" value="{{ request('nid', old('nid_number')) }}"
                                           class="form-control form-control-sm @error('nid_number') is-invalid @enderror">
                                    @error('nid_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">Birth Certificate Number</label>
                                    <input type="text" name="birth_certificate_number" value="{{ old('birth_certificate_number') }}"
                                           class="form-control form-control-sm @error('birth_certificate_number') is-invalid @enderror">
                                    @error('birth_certificate_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">Passport Number</label>
                                    <input type="text" name="passport_number" value="{{ old('passport_number') }}"
                                           class="form-control form-control-sm @error('passport_number') is-invalid @enderror">
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
                const placeholder = document.getElementById('photo-placeholder');

                if (!input || !preview || !placeholder) return;

                input.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.src = e.target.result;
                            preview.style.display = 'block';
                            // Remove d-flex to ensure it hides (Bootstrap !important override)
                            placeholder.classList.remove('d-flex');
                            placeholder.style.display = 'none';
                        }
                        reader.readAsDataURL(file);
                    } else {
                        preview.style.display = 'none';
                        // Restore d-flex
                        placeholder.classList.add('d-flex');
                        placeholder.style.display = '';
                    }
                });

                // Global Patient Existence Check
                const phoneInput = document.querySelector('input[name="phone"]');
                const nidInput = document.querySelector('input[name="nid_number"]');
                const alertBox = document.getElementById('global-search-alert');
                const alertMsg = document.getElementById('alert-message');

                async function checkPatient(field, value) {
                    if (!value || value.length < 5) return;

                    try {
                        const params = new URLSearchParams();
                        params.append(field, value);

                        const response = await fetch(`{{ route('patients.check') }}?${params.toString()}`);
                        const data = await response.json();

                        if (data.exists) {
                            alertBox.classList.remove('d-none');
                            alertBox.classList.add('alert-warning');
                            alertBox.classList.remove('alert-info');

                            let message = `<strong>Existing Patient Found!</strong> ${data.patient.name} (${data.patient.code})`;
                            if (data.patient.is_current_clinic) {
                                message += `<br>This patient is already registered in your clinic. You can search them in the list.`;
                            } else {
                                message += `<br>This patient is registered in another clinic. Saving this form will automatically link them to your clinic.`;
                            }
                            alertMsg.innerHTML = message;
                        } else {
                            // alertBox.classList.add('d-none'); // Don't hide if something was found before, or handle appropriately
                        }
                    } catch (error) {
                        console.error('Error checking patient:', error);
                    }
                }

                phoneInput.addEventListener('blur', (e) => checkPatient('phone', e.target.value));
        nidInput.addEventListener('blur', (e) => checkPatient('nid', e.target.value));

        // Trigger check on load if values exist (e.g. from Global Search redirect)
        if (phoneInput.value) checkPatient('phone', phoneInput.value);
        else if (nidInput.value) checkPatient('nid', nidInput.value);
            });
        </script>
    @endpush

</x-app-layout>
