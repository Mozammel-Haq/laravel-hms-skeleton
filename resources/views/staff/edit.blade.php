<x-app-layout>
    <div class="container-fluid mx-2 mt-2"></div>
        <div class="card p-3 mb-2">
        <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-4 pb-3 rounded-top shadow-sm mb-0">
            <div>
                <h5 class="fw-bold mb-1 text-primary">Edit Staff Member</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-dots mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('staff.index') }}">Staff</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Staff</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('staff.index') }}" class="btn btn-sm btn-outline-primary">
                <i class="ti ti-arrow-left me-1"></i> Back to List
            </a>
        </div>
        </div>
        <div class="card shadow-sm rounded-bottom mt-0">
            <div class="card-body p-4">

                        <form action="{{ route('staff.update', $staff) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row g-4">
                                <!-- Photo Section -->
                                <div class="col-md-3 text-center border-end">
                                    <label class="form-label fw-bold mb-3">Profile Photo</label>
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="mb-3 position-relative">
                                            <img id="photo-preview" src="{{ $staff->profile_photo_url }}"
                                                class="rounded-circle border shadow-sm object-fit-cover" width="120" height="120"
                                                alt="Preview">
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary mb-2" onclick="document.getElementById('profile_photo').click()">
                                            Change Photo
                                        </button>
                                        <input type="file" id="profile_photo" name="profile_photo" class="d-none" accept="image/*,.svg"
                                            onchange="previewPhoto(this)">
                                        <div class="form-text small">Allowed: jpg, jpeg, png. Max: 2MB</div>
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
                                            <label class="form-label small text-muted">Full Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control form-control-sm"
                                                value="{{ old('name', $staff->name) }}" required placeholder="e.g. John Doe">
                                            @error('name')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label small fw-semibold">Email Address (Read Only)</label>
                                            <input type="email" class="form-control form-control-sm bg-light" value="{{ $staff->email }}" readonly disabled>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label small fw-semibold">Role <span class="text-danger">*</span></label>
                                            <select name="role_id" class="form-select form-select-sm" required>
                                                <option value="">Select Role</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}"
                                                        {{ old('role_id', $staff->roles->first()->id ?? '') == $role->id ? 'selected' : '' }}>
                                                        {{ ucfirst($role->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('role_id')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end gap-2 mt-5 border-top pt-3">
                                        <a href="{{ route('staff.index') }}" class="btn btn-sm btn-secondary">Cancel</a>
                                        <button type="submit" class="btn btn-sm btn-primary px-4">Update Staff</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
    </div>

    @push('scripts')
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
    @endpush
</x-app-layout>
