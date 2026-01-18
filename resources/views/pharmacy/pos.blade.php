<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex mt-3 justify-content-between align-items-center mb-4">
            <h3 class="page-title mb-0">New Sale (POS)</h3>
            <a href="{{ route('pharmacy.index') }}" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-1"></i> Back to History
            </a>
        </div>

        <form action="{{ route('pharmacy.store') }}" method="POST" id="pos-form">
            @csrf
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Items</h5>
                            <button type="button" class="btn btn-sm btn-primary" onclick="addItem()">
                                <i class="ti ti-plus me-1"></i> Add Item
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0" id="items-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 50%">Medicine</th>
                                            <th style="width: 20%">Quantity</th>
                                            <th style="width: 10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="items-container">
                                        <!-- Items will be added here -->
                                    </tbody>
                                </table>
                            </div>
                            @error('items') <div class="text-danger mt-2">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-3">Sale Details</h5>

                            @php
                                $selectedPatientId = old('patient_id');
                                if (!$selectedPatientId && isset($prescription) && $prescription->consultation && $prescription->consultation->patient) {
                                    $selectedPatientId = $prescription->consultation->patient->id;
                                }
                                $prefilledPrescriptionId = old('prescription_id');
                                if (!$prefilledPrescriptionId && isset($prescription)) {
                                    $prefilledPrescriptionId = $prescription->id;
                                }
                            @endphp
                            <div class="mb-3">
                                <label class="form-label">Patient <span class="text-danger">*</span></label>
                                <select name="patient_id" class="form-select" required>
                                    <option value="">Select Patient</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" {{ (string) $selectedPatientId === (string) $patient->id ? 'selected' : '' }}>
                                            {{ $patient->name }} ({{ $patient->patient_code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('patient_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Prescription ID <span class="text-danger">*</span></label>
                                <input type="number" name="prescription_id" class="form-control" value="{{ $prefilledPrescriptionId }}" placeholder="Enter Prescription ID" required>
                                <div class="form-text">Required for record keeping.</div>
                                @error('prescription_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <hr>
                            <button type="submit" class="btn btn-success w-100 py-2">
                                <i class="ti ti-check me-1"></i> Complete Sale
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        @php
            $preloadedItems = [];
            if (isset($prescription)) {
                foreach ($prescription->items as $item) {
                    if (!$item->medicine) {
                        continue;
                    }
                    $label = $item->medicine->name;
                    if (!empty($item->medicine->strength)) {
                        $label .= ' (' . $item->medicine->strength . ')';
                    }
                    $preloadedItems[] = [
                        'medicine_id' => $item->medicine->id,
                        'medicine_text' => $label,
                        'quantity' => 1,
                    ];
                }
            }
        @endphp
        const preloadedItems = @json($preloadedItems);

        function initMedicineSelect2(selectEl) {
            const $el = $(selectEl);
            $el.select2({
                width: '100%',
                placeholder: 'Search medicine',
                allowClear: true,
                ajax: {
                    url: '{{ route('pharmacy.medicines.search') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            term: params.term || ''
                        };
                    },
                    processResults: function (data) {
                        return data;
                    },
                    cache: true
                },
                minimumInputLength: 1
            });
        }

        function addItem(prefill = null) {
            const index = document.querySelectorAll('#items-container tr').length;
            const tr = document.createElement('tr');

            tr.innerHTML = `
                <td>
                    <select name="items[${index}][medicine_id]" class="form-select form-select-sm medicine-select" required>
                        <option value="">Search medicine</option>
                    </select>
                </td>
                <td>
                    <input type="number" name="items[${index}][quantity]" class="form-control form-control-sm" min="1" value="1" required>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('tr').remove()">
                        <i class="ti ti-trash"></i>
                    </button>
                </td>
            `;

            document.getElementById('items-container').appendChild(tr);
            const selectEl = tr.querySelector('.medicine-select');
            initMedicineSelect2(selectEl);
            const quantityInput = tr.querySelector('input[name="items[' + index + '][quantity]"]');
            if (prefill && prefill.medicine_id) {
                const option = new Option(prefill.medicine_text || 'Selected medicine', prefill.medicine_id, true, true);
                selectEl.appendChild(option);
                $(selectEl).trigger('change');
            }
            if (prefill && prefill.quantity) {
                quantityInput.value = prefill.quantity;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (Array.isArray(preloadedItems) && preloadedItems.length > 0) {
                preloadedItems.forEach(function (item) {
                    addItem(item);
                });
            } else if (document.querySelectorAll('#items-container tr').length === 0) {
                addItem();
            }
        });
    </script>
</x-app-layout>
