<x-app-layout>
    <div class="container-fluid mx-2">


        <div class="card border-0 mt-2">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="page-title mb-0">Edit Doctor</h4>
                    <a href="{{ route('doctors.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i> Back
                    </a>
                </div>
                <hr>
                <form action="{{ route('doctors.update', $doctor) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Account Info -->
                    <div class="border rounded p-3 mb-3">
                        <h6 class="text-primary mb-2">Account</h6>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control form-control-sm bg-light"
                                    value="{{ $doctor->user->name }}" readonly disabled>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control form-control-sm bg-light"
                                    value="{{ $doctor->user->email }}" readonly disabled>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control form-control-sm"
                                    value="{{ old('phone', $doctor->user->phone) }}">
                            </div>
                        </div>
                        <small class="text-muted">
                            Name & email can be changed from user account. Phone can be updated here.
                        </small>
                    </div>

                    <!-- Professional Info -->
                    <div class="border rounded p-3 mb-3">
                        <h6 class="text-primary mb-2">Professional Profile</h6>

                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label">Department *</label>
                                <select name="primary_department_id" class="form-select form-select-sm" required>
                                    <option value="">Select</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}"
                                            {{ old('primary_department_id', $doctor->primary_department_id) == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Specialization *</label>
                                <select name="specialization[]" class="form-select form-select-sm select2-tags"
                                    multiple="multiple" required>
                                    @if (old('specialization'))
                                        @foreach (old('specialization') as $spec)
                                            <option value="{{ $spec }}" selected>{{ $spec }}</option>
                                        @endforeach
                                    @else
                                        @php
                                            $specData = $doctor->specialization;
                                            $specData = \Illuminate\Support\Arr::wrap($specData);
                                            $finalSpecs = [];
                                            foreach ($specData as $item) {
                                                if (is_string($item)) {
                                                    $decoded = json_decode($item, true);
                                                    if (json_last_error() === JSON_ERROR_NONE) {
                                                        if (is_array($decoded)) {
                                                            foreach (\Illuminate\Support\Arr::flatten($decoded) as $sub) {
                                                                $finalSpecs[] = $sub;
                                                            }
                                                        } else {
                                                            $finalSpecs[] = $decoded;
                                                        }
                                                    } else {
                                                        $finalSpecs[] = $item;
                                                    }
                                                } else {
                                                    $finalSpecs[] = $item;
                                                }
                                            }
                                            $cleanedSpecs = [];
                                            foreach (\Illuminate\Support\Arr::flatten($finalSpecs) as $s) {
                                                if (is_string($s)) {
                                                    foreach (explode(',', $s) as $part) {
                                                        $t = trim($part, " \t\n\r\0\x0B\"'[]");
                                                        if ($t !== '') {
                                                            $cleanedSpecs[] = $t;
                                                        }
                                                    }
                                                }
                                            }
                                            $cleanedSpecs = array_unique($cleanedSpecs);
                                        @endphp
                                        @foreach ($cleanedSpecs as $spec)
                                            <option value="{{ $spec }}" selected>{{ $spec }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">License No *</label>
                                <input type="text" name="license_number" class="form-control form-control-sm"
                                    value="{{ old('license_number', $doctor->license_number) }}" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Registration No</label>
                                <input type="text" name="registration_number" class="form-control form-control-sm"
                                    value="{{ old('registration_number', $doctor->registration_number) }}">
                            </div>
                        </div>

                        <div class="row g-2 mt-2">
                            <div class="col-md-3">
                                <label class="form-label">Experience (Years)</label>
                                <input type="number" name="experience_years" class="form-control form-control-sm"
                                    value="{{ old('experience_years', $doctor->experience_years) }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-select form-select-sm">
                                    <option value="">Select</option>
                                    <option value="male"
                                        {{ old('gender', $doctor->gender) == 'male' ? 'selected' : '' }}>
                                        Male</option>
                                    <option value="female"
                                        {{ old('gender', $doctor->gender) == 'female' ? 'selected' : '' }}>
                                        Female</option>
                                    <option value="other"
                                        {{ old('gender', $doctor->gender) == 'other' ? 'selected' : '' }}>
                                        Other</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">DOB</label>
                                <input type="date" name="date_of_birth" class="form-control form-control-sm"
                                    value="{{ old('date_of_birth', optional($doctor->date_of_birth)->format('Y-m-d')) }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Blood Group</label>
                                <select name="blood_group" class="form-select form-select-sm">
                                    <option value="">Select</option>

                                    @php
                                        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                                        $selectedBloodGroup = old('blood_group', $doctor->blood_group ?? null);
                                    @endphp

                                    @foreach ($bloodGroups as $group)
                                        <option value="{{ $group }}"
                                            {{ $selectedBloodGroup === $group ? 'selected' : '' }}>
                                            {{ $group }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>

                    <!-- Fees + Photo -->
                    <div class="border rounded p-3 mb-3">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label">Consultation Fee</label>
                                <input type="number" step="0.01" name="consultation_fee"
                                    class="form-control form-control-sm"
                                    value="{{ old('consultation_fee', $doctor->consultation_fee) }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Follow-up Fee</label>
                                <input type="number" step="0.01" name="follow_up_fee"
                                    class="form-control form-control-sm"
                                    value="{{ old('follow_up_fee', $doctor->follow_up_fee) }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Consultation Room</label>
                                <input type="text" name="consultation_room_number"
                                    class="form-control form-control-sm"
                                    value="{{ old('consultation_room_number', $doctor->consultation_room_number) }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Consultation Floor</label>
                                <input type="text" name="consultation_floor" class="form-control form-control-sm"
                                    value="{{ old('consultation_floor', $doctor->consultation_floor) }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Profile Photo</label>
                                <input type="file" name="profile_photo" class="form-control form-control-sm"
                                    accept="image/*" onchange="previewDoctorPhoto(event)">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select form-select-sm">
                                    <option value="active"
                                        {{ old('status', $doctor->status) == 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="inactive"
                                        {{ old('status', $doctor->status) == 'inactive' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-3 mt-2">
                            <div id="doctor-photo-preview" class="rounded-circle border"
                                style="width:60px;height:60px;overflow:hidden;">
                                <img src="{{ $doctor->profile_photo ? asset($doctor->profile_photo) : asset('assets/img/doctors/doctor-01.jpg') }}"
                                    style="width:100%;height:100%;object-fit:cover;">
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_featured" value="1"
                                    {{ old('is_featured', $doctor->is_featured) ? 'checked' : '' }}>
                                <label class="form-check-label">
                                    Feature on booking pages
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Bio -->
                    <div class="mb-3">
                        <label class="form-label">Biography</label>
                        <textarea name="biography" class="form-control form-control-sm" rows="2">{{ old('biography', $doctor->biography) }}</textarea>
                    </div>

                    <!-- Actions -->
                    <div class="text-end">
                        <a href="{{ route('doctors.index') }}" class="btn btn-sm btn-light">Cancel</a>
                        <button class="btn btn-sm btn-primary">Update Doctor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function previewDoctorPhoto(event) {
                const img = document.querySelector('#doctor-photo-preview img');
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
