<x-app-layout>
    <div class="card mt-2 mx-2">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Create Clinic Admin</h5>
            <a href="{{ route('admin.clinic-admin-users.index') }}" class="btn btn-primary btn-sm">
                Back
            </a>
        </div>
        <hr>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.clinic-admin-users.store') }}" enctype="multipart/form-data">
                @csrf

                {{-- Profile Photo --}}
                <div class="mb-4">
                    <label class="form-label">Profile Photo</label>
                    <div class="d-flex align-items-center gap-3">
                        <img id="photo-preview" src="{{ asset('assets/img/users/user-01.jpg') }}"
                            class="rounded-circle object-fit-cover" width="80" height="80" alt="Preview">
                        <input type="file" name="profile_photo" class="form-control" accept="image/*"
                            onchange="previewPhoto(this)">
                    </div>
                    <div class="form-text">Allowed: jpg, jpeg, png. Max: 2MB</div>
                    @error('profile_photo')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Name --}}
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Phone --}}
                <div class="mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                        value="{{ old('phone') }}">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Clinic --}}
                <div class="mb-3">
                    <label class="form-label">Clinic</label>
                    <select name="clinic_id" class="form-select @error('clinic_id') is-invalid @enderror" required>
                        <option value="">Select clinic</option>
                        @foreach ($clinics as $clinic)
                            <option value="{{ $clinic->id }}"
                                {{ old('clinic_id') == $clinic->id ? 'selected' : '' }}>
                                {{ $clinic->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('clinic_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                        required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>
                            Active
                        </option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>
                        <option value="blocked" {{ old('status') === 'blocked' ? 'selected' : '' }}>
                            Blocked
                        </option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        Create Clinic Admin
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        function previewPhoto(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('photo-preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-app-layout>
