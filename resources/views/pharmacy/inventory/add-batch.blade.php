<x-app-layout>
    <div class="container-fluid">


        <div class="card border-0 mt-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-1">Add New Batch</h4>
                        <p class="text-muted mb-0">Register new stock batch for medicines</p>
                    </div>
                    <a href="{{ route('pharmacy.inventory.index') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i> Back to Inventory
                    </a>
                </div>
                <form action="{{ route('pharmacy.inventory.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <x-input-label for="medicine_id" :value="__('Select Medicine')" />
                            <select id="medicine_id" name="medicine_id" class="form-select mt-1 block w-full" required>
                                <option value="">Select a medicine...</option>
                                @foreach ($medicines as $medicine)
                                    <option value="{{ $medicine->id }}"
                                        {{ old('medicine_id') == $medicine->id ? 'selected' : '' }}>
                                        {{ $medicine->name }} ({{ $medicine->strength }}) - {{ $medicine->dosage_form }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('medicine_id')" class="mt-2" />
                            @if ($medicines->isEmpty())
                                <div class="form-text text-warning">
                                    No medicines found. <a href="{{ route('pharmacy.medicines.create') }}">Create a
                                        medicine first.</a>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <x-input-label for="batch_number" :value="__('Batch Number')" />
                            <x-text-input id="batch_number" class="block mt-1 w-full form-control" type="text"
                                name="batch_number" :value="old('batch_number')" required placeholder="e.g. BATCH-2023-001" />
                            <x-input-error :messages="$errors->get('batch_number')" class="mt-2" />
                        </div>

                        <div class="col-md-6">
                            <x-input-label for="expiry_date" :value="__('Expiry Date')" />
                            <x-text-input id="expiry_date" class="block mt-1 w-full form-control" type="date"
                                name="expiry_date" :value="old('expiry_date')" required />
                            <x-input-error :messages="$errors->get('expiry_date')" class="mt-2" />
                        </div>

                        <div class="col-md-6">
                            <x-input-label for="quantity_in_stock" :value="__('Quantity Received')" />
                            <x-text-input id="quantity_in_stock" class="block mt-1 w-full form-control" type="number"
                                name="quantity_in_stock" :value="old('quantity_in_stock')" min="1" required />
                            <x-input-error :messages="$errors->get('quantity_in_stock')" class="mt-2" />
                        </div>

                        <div class="col-md-6">
                            <x-input-label for="purchase_price" :value="__('Purchase Price (Total or Unit?)')" />
                            <!-- Note: Controller validation says numeric|min:0. Usually this is unit price or total batch price. Assuming Unit Price based on model structure usually, but let's label it simply Purchase Price -->
                            <div class="input-group mt-1">
                                <span class="input-group-text">$</span>
                                <x-text-input id="purchase_price" class="form-control" type="number" step="0.01"
                                    name="purchase_price" :value="old('purchase_price')" required />
                            </div>
                            <x-input-error :messages="$errors->get('purchase_price')" class="mt-2" />
                            <div class="form-text">Enter the price per unit for this batch.</div>
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary" {{ $medicines->isEmpty() ? 'disabled' : '' }}>
                            <i class="ti ti-device-floppy me-1"></i> Save Batch
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
