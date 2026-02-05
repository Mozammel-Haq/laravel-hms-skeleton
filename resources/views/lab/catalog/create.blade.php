<x-app-layout>
    <div class="container-fluid mx-2 mt-2">
        <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-4 pb-3 rounded-top shadow-sm mb-0">
            <div>
                <h5 class="fw-bold mb-1 text-primary">Create Lab Test</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-dots mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('lab.catalog.index') }}">Lab Test Catalog</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create Lab Test</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('lab.catalog.index') }}" class="btn btn-sm btn-outline-primary">
                <i class="ti ti-arrow-left me-1"></i> Back to List
            </a>
        </div>

        <div class="card rounded-bottom mt-0 shadow-sm border-0">
            <div class="card-body p-3">
            <form method="post" action="{{ route('lab.catalog.store') }}">
                @csrf

                <h5 class="card-title mb-4 pb-2 border-bottom">Test Information</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small text-muted">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted">Category <span class="text-danger">*</span></label>
                        <input type="text" name="category" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label small text-muted">Normal Range</label>
                        <input type="text" name="normal_range" class="form-control form-control-sm">
                    </div>
                </div>

                <h5 class="card-title mb-4 pb-2 border-bottom">Pricing & Status</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small text-muted">Price <span class="text-danger">*</span></label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" name="price" class="form-control form-control-sm" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select form-select-sm" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label small text-muted">Description</label>
                        <textarea name="description" class="form-control form-control-sm" rows="3"></textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('lab.catalog.index') }}" class="btn btn-light">Cancel</a>
                    <button class="btn btn-primary px-4">Save Lab Test</button>
                </div>
            </form>
        </div>
    </div>
</div>
</x-app-layout>
