<x-app-layout>
    <div class="container-fluid mx-2 mt-2">
        <div class="card p-3 mb-2">
                    <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-4 pb-3 rounded shadow-sm">
            <div>
                <h5 class="fw-bold mb-1 text-primary">New Sale (POS)</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-dots mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('pharmacy.index') }}">Pharmacy</a></li>
                        <li class="breadcrumb-item active" aria-current="page">New Sale</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('pharmacy.index') }}" class="btn btn-sm btn-outline-primary">
                <i class="ti ti-arrow-left me-1"></i> Back to History
            </a>
        </div>
        </div>

        <form action="{{ route('pharmacy.store') }}" method="POST" id="pos-form">
            @csrf
            <div class="row g-3">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center border-bottom">
                        <h5 class="mb-0 fw-bold">Items</h5>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addItem()">
                            <i class="ti ti-plus me-1"></i> Add Item
                        </button>
                    </div>
                    <div class="card-body p-2">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="items-table">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50%" class="small fw-bold text-uppercase">Medicine</th>
                                        <th style="width: 20%" class="small fw-bold text-uppercase">Quantity</th>
                                        <th style="width: 10%" class="text-center small fw-bold text-uppercase">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="items-container">
                                    <!-- Items will be added here -->
                                </tbody>
                            </table>
                        </div>
                        @error('items')
                            <div class="text-danger small px-3 py-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-primary fw-bold mb-3 border-bottom pb-2">Sale Details</h5>

                        @php
                            $selectedPatientId = old('patient_id');
                            if (
                                !$selectedPatientId &&
                                isset($prescription) &&
                                $prescription->consultation &&
                                $prescription->consultation->patient
                            ) {
                                $selectedPatientId = $prescription->consultation->patient->id;
                            }
                            $prefilledPrescriptionId = old('prescription_id');
                            if (!$prefilledPrescriptionId && isset($prescription)) {
                                $prefilledPrescriptionId = $prescription->id;
                            }
                        @endphp
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Patient <span class="text-danger">*</span></label>
                            <select name="patient_id" class="form-select form-select-sm select2-patient" required>
                                <option value="">Select Patient</option>
                                @if ($selectedPatientId && isset($patients))
                                    @foreach ($patients as $patient)
                                        @if ((string) $selectedPatientId === (string) $patient->id)
                                            <option value="{{ $patient->id }}" selected>
                                                {{ $patient->name }} ({{ $patient->patient_code }})
                                            </option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                            @error('patient_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Prescription ID <span class="text-muted fw-normal">(Optional)</span></label>
                            <input type="number" name="prescription_id" class="form-control form-control-sm"
                                value="{{ $prefilledPrescriptionId }}" placeholder="Enter Prescription ID">
                            <div class="form-text small">Required only if linked to a prescription.</div>
                            @error('prescription_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label small fw-bold">Discount</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">৳</span>
                                    <input type="number" name="discount" id="discount" class="form-control" step="0.01"
                                        min="0" value="0">
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold">Tax (%)</label>
                                <div class="input-group input-group-sm">
                                    <input type="number" name="tax" id="tax" class="form-control" step="0.01"
                                        min="0" value="0">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Paid Amount</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">৳</span>
                                <input type="number" name="paid_amount" id="paid_amount" class="form-control" step="0.01" min="0" value="0">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Payment Method</label>
                            <select name="payment_method" class="form-select form-select-sm">
                                <option value="cash">Cash</option>
                                <option value="card">Card</option>
                                <option value="mobile_payment">Mobile Payment</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="check">Check</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div class="mt-4 border-top pt-3 bg-light p-3 rounded">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span class="fw-bold">৳ <span id="subtotal-display">0.00</span></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Discount:</span>
                                <span class="text-danger">- ৳ <span id="discount-display">0.00</span></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax:</span>
                                <span class="text-muted">+ ৳ <span id="tax-display">0.00</span></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 border-top pt-2">
                                <span class="h5 fw-bold">Total:</span>
                                <span class="h5 fw-bold text-primary">৳ <span id="total-display">0.00</span></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Paid:</span>
                                <span class="text-success">৳ <span id="paid-display">0.00</span></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Due:</span>
                                <span class="text-danger fw-bold">৳ <span id="due-display">0.00</span></span>
                            </div>
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-sm btn-success w-100 py-2 fw-bold">
                            <i class="ti ti-check me-1"></i> Complete Sale
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Initialize Select2 with AJAX
                $('.select2-patient').select2({
                    ajax: {
                        url: '{{ route('patients.search') }}',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                term: params.term,
                                page: params.page
                            };
                        },
                        processResults: function(data, params) {
                            params.page = params.page || 1;
                            return {
                                results: data.results,
                                pagination: {
                                    more: data.pagination.more
                                }
                            };
                        },
                        cache: true
                    },
                    placeholder: 'Search for a patient',
                    minimumInputLength: 0,
                    allowClear: true,
                    width: '100%'
                });
            });
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
                            'price' => $item->medicine->price,
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
                        data: function(params) {
                            return {
                                term: params.term || '',
                                mode: 'all' // Show all medicines even if out of stock
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

                $el.on('select2:select', function(e) {
                    var data = e.params.data;
                    $(this).closest('tr').attr('data-price', data.price || 0);
                    calculateTotals();
                });

                $el.on('select2:unselect', function(e) {
                    $(this).closest('tr').attr('data-price', 0);
                    calculateTotals();
                });
            }

            function formatMedicine(medicine) {
                if (medicine.loading) {
                    return medicine.text;
                }
                var stockText = medicine.stock !== undefined ? 'Stock: ' + medicine.stock : '';
                // Highlight stock if low/zero
                var badgeClass = medicine.stock > 0 ? 'bg-light text-dark' : 'bg-danger text-white';

                var $container = $(
                    "<div class='d-flex justify-content-between align-items-center'>" +
                        "<span>" + medicine.text + "</span>" +
                        "<span class='badge " + badgeClass + "'>" + stockText + "</span>" +
                    "</div>"
                );
                return $container;
            }

            function formatMedicineSelection(medicine) {
                return medicine.text;
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
                if (prefill && prefill.price) {
                    tr.setAttribute('data-price', prefill.price);
                }
                if (prefill && prefill.quantity) {
                    quantityInput.value = prefill.quantity;
                }
                calculateTotals();
            }

            function calculateTotals() {
                let subtotal = 0;

                $('#items-container tr').each(function() {
                    const price = parseFloat($(this).attr('data-price') || 0);
                    const quantity = parseFloat($(this).find('input[type="number"]').val() || 0);
                    subtotal += price * quantity;
                });

                const discount = parseFloat($('#discount').val() || 0);
                const taxPercent = parseFloat($('#tax').val() || 0);

                const taxAmount = Math.max(0, subtotal - discount) * (taxPercent / 100);
                const total = Math.max(0, subtotal - discount + taxAmount);

                const paid = parseFloat($('#paid_amount').val() || 0);
                const due = Math.max(0, total - paid);

                $('#subtotal-display').text(subtotal.toFixed(2));
                $('#discount-display').text(discount.toFixed(2));
                $('#tax-display').text(taxAmount.toFixed(2));
                $('#total-display').text(total.toFixed(2));
                $('#paid-display').text(paid.toFixed(2));
                $('#due-display').text(due.toFixed(2));
            }

            document.addEventListener('DOMContentLoaded', function() {
                // Attach listeners to static inputs
                $('#discount, #tax, #paid_amount').on('input', calculateTotals);

                // Attach listener to dynamic quantity inputs
                $('#items-container').on('input', 'input[type="number"]', calculateTotals);

                if (Array.isArray(preloadedItems) && preloadedItems.length > 0) {
                    preloadedItems.forEach(function(item) {
                        addItem(item);
                    });
                } else if (document.querySelectorAll('#items-container tr').length === 0) {
                    addItem();
                }
            });
        </script>
    @endpush
</x-app-layout>
