<x-app-layout>
    <div class="container-fluid py-4">
         <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 py-2 pt-3">
            <div>
                <h4 class="font-bold mb-2 text-primary">Create Lab Test</h4>
                {{-- breadcrumb --}}
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-dots mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('lab.catalog.index') }}">Lab Tests</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create Lab Test</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="card p-2 mt-2">
        <div class="card-body">
            <form method="post" action="{{ route('lab.catalog.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Category</label>
                        <input type="text" name="category" class="form-control" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Normal Range</label>
                        <input type="text" name="normal_range" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Price</label>
                        <input type="number" step="0.01" name="price" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="mt-3 d-flex gap-2">
                    <button class="btn btn-primary">Save</button>
                    <a href="{{ route('lab.catalog.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
