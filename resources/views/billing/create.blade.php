<x-app-layout>
    <div class="container-fluid mx-2 mt-2">
        <div class="card shadow-sm border-0 p-3">
            <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-4 pb-3 rounded-top mb-0">
                <div>
                    <h5 class="fw-bold mb-1 text-primary">Create Invoice</h5>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-dots mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('billing.index') }}">Billing</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Create Invoice</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('billing.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="ti ti-arrow-left me-1"></i> Back to List
                </a>
            </div>

            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('billing.store') }}">
                    @csrf

                    <!-- Patient Selection -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Patient</label>
                            <select name="patient_id" id="patientSelect" class="form-select form-select-sm select2-patient" required>
                                <option value="">Select patient</option>
                                @if (isset($patients))
                                    @foreach ($patients as $patient)
                                        <option value="{{ $patient->id }}" selected>
                                            {{ $patient->name }} ({{ $patient->patient_code }})
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Discount</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">৳</span>
                                <input type="number" name="discount" id="discount" class="form-control" step="0.01" value="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Tax (%)</label>
                            <div class="input-group input-group-sm">
                                <input type="number" name="tax" id="tax" class="form-control" step="0.01" value="0">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>

                    <hr class="my-3">

                    <!-- Items Table -->
                    <h5>Invoice Items</h5>
                    <table class="table table-bordered" id="invoiceItemsTable">
                        <thead>
                            <tr>
                                <th style="width:30%">Description</th>
                                <th style="width:15%">Type</th>
                                <th style="width:10%">Quantity</th>
                                <th style="width:15%">Unit Price</th>
                                <th style="width:15%">Total</th>
                                <th style="width:15%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Rows will be added dynamically -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
                                <td colspan="2" class="text-end fw-bold">৳ <span id="subtotal">0.00</span></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Discount:</strong></td>
                                <td colspan="2" class="text-end text-danger">-৳ <span id="discountAmount">0.00</span></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Tax:</strong></td>
                                <td colspan="2" class="text-end">৳ <span id="taxAmount">0.00</span></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Grand Total:</strong></td>
                                <td colspan="2" class="text-end fw-bold text-primary">৳ <span id="grandTotal">0.00</span></td>
                            </tr>
                        </tfoot>
                    </table>

                    <button type="button" class="btn btn-success mb-3" id="addItemBtn" disabled>
                        <i class="ti ti-plus me-1"></i> Add Pending Item
                    </button>
                    <span id="itemsLoadingStatus" class="ms-2 text-muted small" style="display: none;"></span>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Generate Invoice</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Template Row (hidden) -->
    <template id="itemRowTemplate">
        <tr>
            <td>
                <select class="form-select form-select-sm descriptionSelect" name="items[][reference_id]" required>
                    <option value="">Select Item</option>
                </select>
            </td>
            <td>
                <input type="text" name="items[][item_type]" class="form-control form-control-sm itemType" readonly>
            </td>
            <td>
                <input type="number" name="items[][quantity]" class="form-control form-control-sm quantity" min="1"
                    value="1">
            </td>
            <td>
                <div class="input-group input-group-sm">
                    <span class="input-group-text">৳</span>
                    <input type="number" name="items[][unit_price]" class="form-control unitPrice" step="0.01"
                        value="0">
                </div>
            </td>
            <td>
                <div class="input-group input-group-sm">
                    <span class="input-group-text">৳</span>
                    <input type="number" name="items[][total]" class="form-control total" step="0.01" value="0"
                        readonly>
                </div>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm removeItemBtn">Remove</button>
            </td>
        </tr>
    </template>

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

            let itemsData = {}; // Will hold AJAX fetched items
            const patientSelect = document.getElementById('patientSelect');
            const addItemBtn = document.getElementById('addItemBtn');
            const itemsLoadingStatus = document.getElementById('itemsLoadingStatus');
            const tableBody = document.querySelector('#invoiceItemsTable tbody');

            // Fetch pending items when patient changes
            $('.select2-patient').on('select2:select', function(e) {
                const patientId = e.params.data.id;
                loadPatientItems(patientId);
            });

             // Also handle manual change if needed (though Select2 handles it mostly)
             $(patientSelect).on('change', function() {
                 if(this.value && !itemsData.consultations) { // check if not already loaded
                    loadPatientItems(this.value);
                 }
             });

            function loadPatientItems(patientId) {
                if (!patientId) {
                    addItemBtn.disabled = true;
                    return;
                }

                itemsLoadingStatus.style.display = 'inline';
                itemsLoadingStatus.innerText = 'Searching for pending items...';
                itemsLoadingStatus.className = 'ms-2 text-muted small';
                addItemBtn.disabled = true;

                fetch(`/billing/patient-items/${patientId}`)
                    .then(res => res.json())
                    .then(data => {
                        itemsData = data; // { consultations: [], lab_tests: [], medicines: [] }
                        const totalItems = (data.consultations?.length || 0) + (data.lab_tests?.length || 0) + (data.medicines?.length || 0);

                        itemsLoadingStatus.style.display = 'inline';
                        if (totalItems > 0) {
                            itemsLoadingStatus.innerText = `${totalItems} pending item(s) found.`;
                            itemsLoadingStatus.className = 'ms-2 text-success small fw-bold';
                            addItemBtn.disabled = false;
                        } else {
                            itemsLoadingStatus.innerText = 'No pending billable items found for this patient.';
                            itemsLoadingStatus.className = 'ms-2 text-danger small fw-bold';
                            addItemBtn.disabled = true;
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        itemsLoadingStatus.innerText = 'Error loading items.';
                        itemsLoadingStatus.className = 'ms-2 text-danger small';
                    });
            }

            // Add item row
            let itemRowIndex = 0;
            addItemBtn.addEventListener('click', function() {
                const template = document.getElementById('itemRowTemplate').content.cloneNode(true);
                const row = template.querySelector('tr');

                // Update names with index
                row.querySelector('.descriptionSelect').name = `items[${itemRowIndex}][reference_id]`;
                row.querySelector('.itemType').name = `items[${itemRowIndex}][item_type]`;
                row.querySelector('.quantity').name = `items[${itemRowIndex}][quantity]`;
                row.querySelector('.unitPrice').name = `items[${itemRowIndex}][unit_price]`;
                // total is not submitted but good to keep consistent if needed
                row.querySelector('.total').name = `items[${itemRowIndex}][total]`;

                const select = template.querySelector('.descriptionSelect');

                // Populate options from fetched items
                ['consultations', 'lab_tests', 'medicines'].forEach(group => {
                    if (itemsData[group]) {
                        itemsData[group].forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.id;
                            option.dataset.type = item.type;
                            option.dataset.price = item.price;
                            option.text = item.description;
                            select.appendChild(option);
                        });
                    }
                });

                tableBody.appendChild(template);
                itemRowIndex++;
            });

            // Event delegation for dynamic rows
            tableBody.addEventListener('change', function(e) {
                if (e.target.classList.contains('descriptionSelect')) {
                    const row = e.target.closest('tr');
                    const selectedOption = e.target.selectedOptions[0];
                    row.querySelector('.itemType').value = selectedOption.dataset.type || '';
                    row.querySelector('.unitPrice').value = selectedOption.dataset.price || 0;
                    updateRowTotal(row);
                    updateTotals();
                }
            });

            tableBody.addEventListener('input', function(e) {
                if (e.target.classList.contains('quantity') || e.target.classList.contains('unitPrice')) {
                    const row = e.target.closest('tr');
                    updateRowTotal(row);
                    updateTotals();
                }
            });

            tableBody.addEventListener('click', function(e) {
                if (e.target.classList.contains('removeItemBtn')) {
                    e.target.closest('tr').remove();
                    updateTotals();
                }
            });

            function updateRowTotal(row) {
                const qty = parseFloat(row.querySelector('.quantity').value) || 0;
                const price = parseFloat(row.querySelector('.unitPrice').value) || 0;
                row.querySelector('.total').value = (qty * price).toFixed(2);
            }

            function updateTotals() {
                let subtotal = 0;
                tableBody.querySelectorAll('tr').forEach(row => {
                    subtotal += parseFloat(row.querySelector('.total').value) || 0;
                });
                const discount = parseFloat(document.getElementById('discount').value) || 0;
                const tax = parseFloat(document.getElementById('tax').value) || 0;

                const discountAmount = discount;
                const taxAmount = ((subtotal - discountAmount) * tax / 100).toFixed(2);
                const grandTotal = (subtotal - discountAmount + parseFloat(taxAmount)).toFixed(2);

                document.getElementById('subtotal').innerText = subtotal.toFixed(2);
                document.getElementById('discountAmount').innerText = discountAmount.toFixed(2);
                document.getElementById('taxAmount').innerText = taxAmount;
                document.getElementById('grandTotal').innerText = grandTotal;
            }

            // Update totals when discount or tax changes
            document.getElementById('discount').addEventListener('input', updateTotals);
            document.getElementById('tax').addEventListener('input', updateTotals);
        </script>
    @endpush
</x-app-layout>
