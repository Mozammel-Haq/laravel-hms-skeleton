<x-app-layout>
    <div class="container py-4">

        <!-- Page Header -->


        <div class="card mt-3 mx-2">
            <div class="card-body">
                <div class="mb-4">
                    <h4 class="fw-semibold">Edit Patient</h4>
                </div>
                <hr>
                <form method="POST" action="{{ route('patients.update', $patient) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        <!-- Profile Photo -->
                        <div class="col-12">
                            <label for="profile_photo" class="form-label">Profile Photo</label>
                            @if($patient->profile_photo)
                                <div class="mb-2">
                                    <img src="{{ asset($patient->profile_photo) }}" alt="Profile Photo" class="rounded" style="width: 100px; height: 100px; object-fit: cover;">
                                </div>
                            @endif
                            <input type="file" id="profile_photo" name="profile_photo" class="form-control @error('profile_photo') is-invalid @enderror">
                            @error('profile_photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Full Name -->
                        <div class="col-12">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name', $patient->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Date of Birth -->
                        <div class="col-md-6">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                                id="date_of_birth" name="date_of_birth"
                                value="{{ old('date_of_birth', $patient->date_of_birth) }}" required>
                            @error('date_of_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div class="col-md-6">
                            <label for="gender" class="form-label">Gender</label>
                            <select id="gender" name="gender"
                                class="form-select @error('gender') is-invalid @enderror">
                                <option value="">Select Gender</option>
                                <option value="male"
                                    {{ old('gender', $patient->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female"
                                    {{ old('gender', $patient->gender) === 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other"
                                    {{ old('gender', $patient->gender) === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                id="phone" name="phone" value="{{ old('phone', $patient->phone) }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email (Optional)</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email', $patient->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Blood Group -->
                        <div class="col-md-6">
                            <label for="blood_group" class="form-label">Blood Group (Optional)</label>
                            <select id="blood_group" name="blood_group"
                                class="form-select @error('blood_group') is-invalid @enderror">
                                <option value="">Select Blood Group</option>
                                @foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $group)
                                    <option value="{{ $group }}"
                                        {{ old('blood_group', $patient->blood_group) === $group ? 'selected' : '' }}>
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
                            <label for="address" class="form-label">Address</label>
                            <textarea id="address" name="address" rows="3" class="form-control @error('address') is-invalid @enderror"
                                required>{{ old('address', $patient->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Emergency Contact Name -->
                        <div class="col-md-6">
                            <label for="emergency_contact_name" class="form-label">Emergency Contact Name</label>
                            <input type="text"
                                class="form-control @error('emergency_contact_name') is-invalid @enderror"
                                id="emergency_contact_name" name="emergency_contact_name"
                                value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}">
                            @error('emergency_contact_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Emergency Contact Phone -->
                        <div class="col-md-6">
                            <label for="emergency_contact_phone" class="form-label">Emergency Contact Phone</label>
                            <input type="text"
                                class="form-control @error('emergency_contact_phone') is-invalid @enderror"
                                id="emergency_contact_phone" name="emergency_contact_phone"
                                value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone) }}">
                            @error('emergency_contact_phone')
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
                            Update Patient
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
