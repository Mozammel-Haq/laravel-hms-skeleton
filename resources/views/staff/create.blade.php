<x-app-layout>
    <div class="container-fluid mx-2 mt-2">
        <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-4 pb-3 rounded-top shadow-sm mb-0">
            <div>
                <h5 class="fw-bold mb-1 text-primary">Add Staff Member</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-dots mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('staff.index') }}">Staff</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Staff</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('staff.index') }}" class="btn btn-sm btn-outline-primary">
                <i class="ti ti-arrow-left me-1"></i> Back to List
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow-sm rounded-bottom mt-0">
                    <div class="card-body p-4">
                        <form action="{{ route('staff.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-4">
                                <!-- Photo Section -->
                                <div class="col-md-3 text-center border-end">
                                    <label class="form-label fw-bold mb-3">Profile Photo</label>
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="mb-3 position-relative">
                                            <img id="photo-preview" src="{{ asset('assets/img/users/user-01.jpg') }}"
                                                class="rounded-circle border shadow-sm object-fit-cover" width="120" height="120"
                                                alt="Preview">
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary mb-2" onclick="document.getElementById('profile_photo').click()">
                                            Upload Photo
                                        </button>
                                        <input type="file" id="profile_photo" name="profile_photo" class="d-none" accept="image/*,.svg"
                                            onchange="previewPhoto(this)">
                                        <div class="text-muted small">Allowed: jpg, jpeg, png. Max: 2MB</div>
                                        @error('profile_photo')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Form Fields -->
                                <div class="col-md-9">
                                    <h5 class="text-primary fw-bold mb-3">Account Details</h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label small fw-semibold">Full Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control form-control-sm" value="{{ old('name') }}" required placeholder="e.g. John Doe">
                                            @error('name')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label small fw-semibold">Email Address <span class="text-danger">*</span></label>
                                            <input type="email" name="email" class="form-control form-control-sm" value="{{ old('email') }}" required placeholder="e.g. john@example.com">
                                            @error('email')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label small fw-semibold">Password <span class="text-danger">*</span></label>
                                            <input type="password" name="password" class="form-control form-control-sm" required minlength="8" placeholder="Min 8 characters">
                                            @error('password')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label small fw-semibold">Role <span class="text-danger">*</span></label>
                                            <select name="role_id" class="form-select form-select-sm" required>
                                                <option value="">Select Role</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                        {{ ucfirst($role->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('role_id')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                                        <a href="{{ route('staff.index') }}" class="btn btn-sm btn-secondary">Cancel</a>
                                        <button type="submit" class="btn btn-sm btn-primary px-4">Create Account</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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
