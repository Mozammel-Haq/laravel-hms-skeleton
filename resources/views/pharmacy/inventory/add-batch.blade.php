<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-2 bg-primary-subtle text-primary px-4 pt-4 pb-3 pt-3">
        <div>
            <h4 class="fw-bold mb-2 text-primary">Add New Batch</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-dots mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('pharmacy.inventory.index') }}">Inventory</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add New Batch</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('pharmacy.inventory.index') }}" class="btn btn-sm btn-outline-primary">
            <i class="ti ti-arrow-left me-1"></i> Back to Batches
        </a>
    </div>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-body p-4">
                        <form action="{{ route('pharmacy.inventory.store') }}" method="POST">
                            @csrf

                            <h5 class="text-primary fw-bold mb-3 border-bottom pb-2">Batch Details</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-12">
                                    <label class="form-label small fw-bold text-dark">Select Medicine <span class="text-danger">*</span></label>
                                    <select id="medicine_id" name="medicine_id" class="form-select form-select-sm" required>
                                        <option value="">Select a medicine...</option>
                                        @foreach ($medicines as $medicine)
                                            <option value="{{ $medicine->id }}"
                                                {{ old('medicine_id') == $medicine->id ? 'selected' : '' }}>
                                                {{ $medicine->name }} ({{ $medicine->strength }}) - {{ $medicine->dosage_form }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('medicine_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                    @if ($medicines->isEmpty())
                                        <div class="form-text text-warning small">
                                            No medicines found. <a href="{{ route('pharmacy.medicines.create') }}">Create a medicine first.</a>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-dark">Batch Number <span class="text-danger">*</span></label>
                                    <input type="text" name="batch_number" class="form-control form-control-sm"
                                        value="{{ old('batch_number') }}" required placeholder="e.g. BATCH-2023-001">
                                    @error('batch_number') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-dark">Expiry Date <span class="text-danger">*</span></label>
                                    <input type="date" name="expiry_date" class="form-control form-control-sm"
                                        value="{{ old('expiry_date') }}" required>
                                    @error('expiry_date') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <h5 class="text-primary fw-bold mb-3 border-bottom pb-2">Stock & Pricing</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-dark">Quantity Received <span class="text-danger">*</span></label>
                                    <input type="number" name="quantity_in_stock" class="form-control form-control-sm"
                                        value="{{ old('quantity_in_stock') }}" min="1" required>
                                    @error('quantity_in_stock') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-dark">Purchase Price <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">৳</span>
                                        <input type="number" name="purchase_price" class="form-control" step="0.01"
                                            value="{{ old('purchase_price') }}" required>
                                    </div>
                                    @error('purchase_price') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                    <div class="form-text small">Enter the price per unit for this batch.</div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('pharmacy.inventory.index') }}" class="btn btn-sm btn-light border">Cancel</a>
                                <button type="submit" class="btn btn-sm btn-primary" {{ $medicines->isEmpty() ? 'disabled' : '' }}>
                                    <i class="ti ti-device-floppy me-1"></i> Save Batch
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
