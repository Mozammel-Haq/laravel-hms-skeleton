<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark mb-0">
            Add New Patient
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-body">

                    <form method="POST" action="{{ route('patients.store') }}">
                        @csrf

                        <div class="row g-3">

                            <!-- Full Name -->
                            <div class="col-12">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}"
                                    class="form-control @error('name') is-invalid @enderror" required autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date of Birth -->
                            <div class="col-md-6">
                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                <input type="date" id="date_of_birth" name="date_of_birth"
                                    value="{{ old('date_of_birth') }}"
                                    class="form-control @error('date_of_birth') is-invalid @enderror" required>
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Gender -->
                            <div class="col-md-6">
                                <label for="gender" class="form-label">Gender</label>
                                <select id="gender" name="gender"
                                    class="form-select @error('gender') is-invalid @enderror" required>
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male
                                    </option>
                                    <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female
                                    </option>
                                    <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other
                                    </option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                                    class="form-control @error('phone') is-invalid @enderror" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email (Optional)</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                    class="form-control @error('email') is-invalid @enderror">
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
                                            {{ old('blood_group') === $group ? 'selected' : '' }}>
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
                                    required>{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Emergency Contact Name -->
                            <div class="col-md-6">
                                <label for="emergency_contact_name" class="form-label">
                                    Emergency Contact Name
                                </label>
                                <input type="text" id="emergency_contact_name" name="emergency_contact_name"
                                    value="{{ old('emergency_contact_name') }}"
                                    class="form-control @error('emergency_contact_name') is-invalid @enderror">
                                @error('emergency_contact_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Emergency Contact Phone -->
                            <div class="col-md-6">
                                <label for="emergency_contact_phone" class="form-label">
                                    Emergency Contact Phone
                                </label>
                                <input type="text" id="emergency_contact_phone" name="emergency_contact_phone"
                                    value="{{ old('emergency_contact_phone') }}"
                                    class="form-control @error('emergency_contact_phone') is-invalid @enderror">
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
                                Save Patient
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
