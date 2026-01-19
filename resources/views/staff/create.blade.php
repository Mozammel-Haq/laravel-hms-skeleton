<x-app-layout>
    <div class="container-fluid mx-2">


        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card border-0 mt-2">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="page-title mb-0">Add Staff Member</h3>
                            <a href="{{ route('staff.index') }}" class="btn btn-outline-primary">
                                <i class="ti ti-arrow-left me-1"></i> Back to List
                            </a>
                        </div>
                        <hr>
                        <form action="{{ route('staff.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <h5 class="card-title text-primary mb-3">Account Details</h5>

                            <div class="mb-4">
                                <label class="form-label">Profile Photo</label>
                                <div class="d-flex align-items-center gap-3">
                                    <img id="photo-preview" src="{{ asset('assets/img/users/user-01.jpg') }}"
                                        class="rounded-circle object-fit-cover" width="80" height="80"
                                        alt="Preview">
                                    <input type="file" name="profile_photo" class="form-control" accept="image/*"
                                        onchange="previewPhoto(this)">
                                </div>
                                <div class="form-text">Allowed: jpg, jpeg, png. Max: 2MB</div>
                                @error('profile_photo')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                    required>
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                    required>
                                <div class="form-text">This will be used for login.</div>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" required minlength="8">
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Role <span class="text-danger">*</span></label>
                                <select name="role_id" class="form-select" required>
                                    <option value="">Select Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('staff.index') }}" class="btn btn-light">Cancel</a>
                                <button type="submit" class="btn btn-primary">Create Account</button>
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
