<x-app-layout>
    <div class="container-fluid mx-2 mt-2">
        <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 py-2 pt-3">
            <div>
                <h4 class="font-bold mb-2 text-primary">Edit Ward</h4>
                {{-- breadcrumb --}}
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-dots mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('ipd.index') }}">IPD</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('ipd.wards.index') }}">Wards</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Ward</li>
                    </ol>
                </nav>
            </div>
        </div>
    <div class="card p-3">
        <div class="card-body">
            <form method="post" action="{{ route('ipd.wards.update', $ward) }}">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $ward->name }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select" required>
                            <option value="general" @if ($ward->type === 'general') selected @endif>General</option>
                            <option value="icu" @if ($ward->type === 'icu') selected @endif>ICU</option>
                            <option value="cabin" @if ($ward->type === 'cabin') selected @endif>Cabin</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Floor</label>
                        <input type="number" name="floor" class="form-control" value="{{ $ward->floor }}">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ $ward->description }}</textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="active" @if ($ward->status === 'active') selected @endif>Active</option>
                            <option value="inactive" @if ($ward->status === 'inactive') selected @endif>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="mt-3 d-flex gap-2">
                    <button class="btn btn-primary">Update</button>
                    <a href="{{ route('ipd.wards.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
