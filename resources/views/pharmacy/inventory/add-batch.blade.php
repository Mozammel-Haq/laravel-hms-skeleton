<x-app-layout>

    <div class="container-fluid">
        <div class="card p-3 mb-0">
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
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-body p-4">
                        <form action="{{ route('pharmacy.inventory.store') }}" method="POST">
                            @csrf

                            <h5 class="text-primary fw-bold mb-3 border-bottom pb-2">Batch Details</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-12">
                                    <label class="form-label small fw-bold text-dark">Select Medicine <span class="text-danger">*</span></label>
                                    <select id="medicine_id" name="medicine_id" class="form-select form-select-sm select2-medicine" required>
                                        <option value="">Select a medicine...</option>
                                        @if(old('medicine_id'))
                                            @php
                                                $oldMed = $medicines->firstWhere('id', old('medicine_id'));
                                            @endphp
                                            @if($oldMed)
                                                <option value="{{ $oldMed->id }}" selected>
                                                    {{ $oldMed->name }} ({{ $oldMed->strength }}) - {{ $oldMed->dosage_form }}
                                                </option>
                                            @endif
                                        @endif
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
    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                initMedicineSelect2($('.select2-medicine'));

                function initMedicineSelect2(element) {
                    element.select2({
                        theme: 'bootstrap-5',
                        width: '100%',
                        placeholder: 'Search medicine...',
                        allowClear: true,
                        ajax: {
                            url: '{{ route('pharmacy.medicines.search') }}',
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                return {
                                    term: params.term || '',
                                    mode: 'all' // We need to see all medicines to add stock
                                };
                            },
                            processResults: function(data) {
                                return {
                                    results: data.results,
                                    pagination: {
                                        more: data.pagination.more
                                    }
                                };
                            },
                            cache: true
                        },
                        minimumInputLength: 0,
                        templateResult: formatMedicine,
                        templateSelection: formatMedicineSelection
                    });
                }

                function formatMedicine(medicine) {
                    if (medicine.loading) {
                        return medicine.text;
                    }
                    var stockText = medicine.stock !== undefined ? 'Stock: ' + medicine.stock : '';
                    var $container = $(
                        "<div class='d-flex justify-content-between align-items-center'>" +
                            "<span>" + medicine.text + "</span>" +
                            "<span class='badge bg-light text-dark'>" + stockText + "</span>" +
                        "</div>"
                    );
                    return $container;
                }

                function formatMedicineSelection(medicine) {
                    return medicine.text;
                }
            });
        </script>
    @endpush
</x-app-layout>
