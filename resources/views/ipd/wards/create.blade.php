<x-app-layout>
    <div class="container-fluid mx-2 mt-2">
        <div class="card shadow-sm border-0">
            <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-4 pb-3 rounded-top">
                <div>
                    <h5 class="fw-bold mb-1 text-primary">Create Ward</h5>
                    {{-- breadcrumb --}}
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-dots mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('ipd.index') }}">IPD</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('ipd.wards.index') }}">Wards</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Create Ward</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('ipd.wards.index') }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-arrow-left me-1"></i> Back to Wards
                    </a>
                </div>
            </div>

            <div class="card-body p-3">
                <form method="post" action="{{ route('ipd.wards.store') }}">
                    @csrf

                    <h5 class="card-title mb-4 border-bottom pb-2 text-primary">Ward Information</h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small text-muted mb-1">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-sm" required placeholder="e.g. General Ward 1">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted mb-1">Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select form-select-sm" required>
                                <option value="">Select Type</option>
                                <option value="general">General</option>
                                <option value="icu">ICU</option>
                                <option value="cabin">Cabin</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted mb-1">Floor</label>
                            <input type="number" name="floor" class="form-control form-control-sm" placeholder="e.g. 1">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted mb-1">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select form-select-sm" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small text-muted mb-1">Description</label>
                            <textarea name="description" class="form-control form-control-sm" rows="3" placeholder="Enter ward description..."></textarea>
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2 justify-content-end">
                        <a href="{{ route('ipd.wards.index') }}" class="btn btn-sm btn-light border">Cancel</a>
                        <button class="btn btn-sm btn-primary px-4">Save Ward</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
