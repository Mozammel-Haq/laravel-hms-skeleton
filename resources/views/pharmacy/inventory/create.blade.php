<x-app-layout>
    <div class="container-fluid mx-2 mt-2">
        <div class="card p-2 mb-2">
        <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary p-3 rounded mx-2 shadow-sm">
            <div>
                <h4 class="font-bold mb-2 text-primary">{{ isset($medicine) ? 'Edit Medicine' : 'Add New Medicine' }}</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-dots mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('pharmacy.medicines.index') }}">Inventory</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ isset($medicine) ? 'Edit' : 'Create' }}</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('pharmacy.medicines.index') }}" class="btn btn-sm btn-outline-primary">
                <i class="ti ti-arrow-left me-1"></i> Back to Catalog
            </a>
        </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form action="{{ isset($medicine) ? route('pharmacy.medicines.update', $medicine) : route('pharmacy.medicines.store') }}"
                            method="POST">
                            @csrf
                            @if (isset($medicine))
                                @method('PUT')
                            @endif

                            <h5 class="card-title mb-4 border-bottom pb-2 text-primary">Medicine Details</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label small text-muted mb-1">Medicine Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control form-control-sm"
                                        value="{{ old('name', $medicine->name ?? '') }}" required autofocus>
                                    @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small text-muted mb-1">Generic Name</label>
                                    <input type="text" name="generic_name" class="form-control form-control-sm"
                                        value="{{ old('generic_name', $medicine->generic_name ?? '') }}">
                                    @error('generic_name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small text-muted mb-1">Manufacturer</label>
                                    <input type="text" name="manufacturer" class="form-control form-control-sm"
                                        value="{{ old('manufacturer', $medicine->manufacturer ?? '') }}">
                                    @error('manufacturer') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small text-muted mb-1">Strength</label>
                                    <input type="text" name="strength" class="form-control form-control-sm"
                                        value="{{ old('strength', $medicine->strength ?? '') }}" placeholder="e.g. 500mg">
                                    @error('strength') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <h5 class="card-title mb-4 border-bottom pb-2 text-primary">Classification & Pricing</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <label class="form-label small text-muted mb-1">Dosage Form</label>
                                    <select name="dosage_form" class="form-select form-select-sm">
                                        <option value="">Select Dosage Form</option>
                                        @foreach (['Tablet', 'Capsule', 'Syrup', 'Injection', 'Ointment', 'Drops', 'Inhaler'] as $form)
                                            <option value="{{ $form }}" {{ old('dosage_form', $medicine->dosage_form ?? '') == $form ? 'selected' : '' }}>
                                                {{ $form }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('dosage_form') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label small text-muted mb-1">Unit Price <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">৳</span>
                                        <input type="number" name="price" class="form-control" step="0.01"
                                            value="{{ old('price', $medicine->price ?? '') }}" required>
                                    </div>
                                    @error('price') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label small text-muted mb-1">Status</label>
                                    <select name="status" class="form-select form-select-sm">
                                        <option value="active" {{ old('status', $medicine->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $medicine->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('pharmacy.medicines.index') }}" class="btn btn-sm btn-light border">Cancel</a>
                                <button type="submit" class="btn btn-sm btn-primary px-4">
                                    <i class="ti ti-device-floppy me-1"></i>
                                    {{ isset($medicine) ? 'Update Medicine' : 'Save Medicine' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
