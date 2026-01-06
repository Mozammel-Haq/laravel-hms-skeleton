<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="page-title mb-0">Create Invoice</h3>
            <a href="{{ route('billing.index') }}" class="btn btn-outline-secondary">Invoices</a>
        </div>
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('billing.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Patient</label>
                            <select name="patient_id" class="form-select" required>
                                <option value="">Select patient</option>
                                @foreach ($patients as $p)
                                    <option value="{{ $p->id }}">{{ $p->full_name ?? $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Discount</label>
                            <input type="number" name="discount" class="form-control" step="0.01" value="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tax</label>
                            <input type="number" name="tax" class="form-control" step="0.01" value="0">
                        </div>
                    </div>

                    <hr class="my-4">
                    <h5>Items</h5>
                    @for ($i = 0; $i < 3; $i++)
                        <div class="row g-2 align-items-end mb-2">
                            <div class="col-md-4">
                                <label class="form-label">Description</label>
                                <input type="text" name="items[{{ $i }}][description]" class="form-control" placeholder="Service or Medicine">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Type</label>
                                <select name="items[{{ $i }}][item_type]" class="form-select">
                                    <option value="">Select</option>
                                    <option value="service">Service</option>
                                    <option value="medicine">Medicine</option>
                                    <option value="consultation">Consultation</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Quantity</label>
                                <input type="number" name="items[{{ $i }}][quantity]" class="form-control" min="1" value="1">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Unit Price</label>
                                <input type="number" name="items[{{ $i }}][unit_price]" class="form-control" step="0.01" value="0">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Reference ID</label>
                                <input type="text" name="items[{{ $i }}][reference_id]" class="form-control" placeholder="Optional">
                            </div>
                        </div>
                    @endfor

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Generate Invoice</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
