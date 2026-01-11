<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="page-title mb-0">Edit Doctor</h3>
            <a href="{{ route('doctors.index') }}" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-1"></i> Back to List
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form action="{{ route('doctors.update', $doctor) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <h5 class="card-title text-primary mb-3">Professional Information</h5>

                                <div class="mb-3">
                                    <label class="form-label text-muted">Doctor Name (Read Only)</label>
                                    <input type="text" class="form-control bg-light" value="{{ $doctor->user->name }}" readonly disabled>
                                    <div class="form-text">To change name/email, please edit the user account directly.</div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Specialization <span class="text-danger">*</span></label>
                                        <input type="text" name="specialization" class="form-control" value="{{ old('specialization', $doctor->specialization) }}" required>
                                        @error('specialization') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">License Number <span class="text-danger">*</span></label>
                                        <input type="text" name="license_number" class="form-control" value="{{ old('license_number', $doctor->license_number) }}" required>
                                        @error('license_number') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h5 class="card-title text-primary mb-3">Account Status</h5>
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="active" {{ old('status', $doctor->status) === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $doctor->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('doctors.index') }}" class="btn btn-light">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update Doctor</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
