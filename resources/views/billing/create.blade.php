<x-app-layout>
    <div class="container-fluid">


        <div class="card m-2">
            <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="page-title mb-0">Create Invoice</h3>

            <a href="{{ route('billing.index') }}" class="btn btn-outline-secondary">Invoices</a>
        </div>
        <hr>
                <form method="POST" action="{{ route('billing.store') }}">
                    @csrf

                    <!-- Patient Selection -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Patient</label>
                            <select name="patient_id" id="patientSelect" class="form-select" required>
                                <option value="">Select patient</option>
                                @foreach ($patients as $p)
                                    <option value="{{ $p->id }}">{{ $p->full_name ?? $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Discount</label>
                            <input type="number" name="discount" id="discount" class="form-control" step="0.01"
                                value="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tax (%)</label>
                            <input type="number" name="tax" id="tax" class="form-control" step="0.01"
                                value="0">
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
                                <td colspan="2" id="subtotal">0.00</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Discount:</strong></td>
                                <td colspan="2" id="discountAmount">0.00</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Tax:</strong></td>
                                <td colspan="2" id="taxAmount">0.00</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Grand Total:</strong></td>
                                <td colspan="2" id="grandTotal">0.00</td>
                            </tr>
                        </tfoot>
                    </table>

                    <button type="button" class="btn btn-success mb-3" id="addItemBtn">Add Item</button>

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
                <select class="form-select descriptionSelect" name="items[][reference_id]" required>
                    <option value="">Select Item</option>
                </select>
            </td>
            <td>
                <input type="text" name="items[][item_type]" class="form-control itemType" readonly>
            </td>
            <td>
                <input type="number" name="items[][quantity]" class="form-control quantity" min="1"
                    value="1">
            </td>
            <td>
                <input type="number" name="items[][unit_price]" class="form-control unitPrice" step="0.01"
                    value="0">
            </td>
            <td>
                <input type="number" name="items[][total]" class="form-control total" step="0.01" value="0"
                    readonly>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm removeItemBtn">Remove</button>
            </td>
        </tr>
    </template>

    <script>
        let itemsData = {}; // Will hold AJAX fetched items
        const patientSelect = document.getElementById('patientSelect');
        const addItemBtn = document.getElementById('addItemBtn');
        const tableBody = document.querySelector('#invoiceItemsTable tbody');

        // Fetch pending items when patient changes
        patientSelect.addEventListener('change', function() {
            const patientId = this.value;
            if (!patientId) return;

            fetch(`/billing/patient-items/${patientId}`)
                .then(res => res.json())
                .then(data => {
                    itemsData = data; // { consultations: [], lab_tests: [], medicines: [] }
                });
        });

        // Add item row
        addItemBtn.addEventListener('click', function() {
            const template = document.getElementById('itemRowTemplate').content.cloneNode(true);
            const select = template.querySelector('.descriptionSelect');

            // Populate options from fetched items
            ['consultations', 'lab_tests', 'medicines'].forEach(type => {
                if (itemsData[type]) {
                    itemsData[type].forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.dataset.type = type;
                        option.dataset.price = item.price;
                        option.text = item.description;
                        select.appendChild(option);
                    });
                }
            });

            tableBody.appendChild(template);
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
</x-app-layout>
