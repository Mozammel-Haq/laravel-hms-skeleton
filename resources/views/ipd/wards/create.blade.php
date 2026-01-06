<x-app-layout>
    <h5 class="mb-3">Create Ward</h5>
    <div class="card">
        <div class="card-body">
            <form method="post" action="{{ route('ipd.wards.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select" required>
                            <option value="general">General</option>
                            <option value="icu">ICU</option>
                            <option value="cabin">Cabin</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Floor</label>
                        <input type="number" name="floor" class="form-control">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="mt-3 d-flex gap-2">
                    <button class="btn btn-primary">Save</button>
                    <a href="{{ route('ipd.wards.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
