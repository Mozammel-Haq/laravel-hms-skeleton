<x-app-layout>
    <div class="container-fluid">


        <div class="card mt-2 mx-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-1">{{ isset($medicine) ? 'Edit Medicine' : 'Add New Medicine' }}</h4>
                        <p class="text-muted mb-0">
                            {{ isset($medicine) ? 'Update medicine details' : 'Register a new medicine in the catalog' }}
                        </p>
                    </div>
                    <a href="{{ route('pharmacy.medicines.index') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i> Back to Catalog
                    </a>
                </div>
                <hr>
                <form
                    action="{{ isset($medicine) ? route('pharmacy.medicines.update', $medicine) : route('pharmacy.medicines.store') }}"
                    method="POST">
                    @csrf
                    @if (isset($medicine))
                        @method('PUT')
                    @endif

                    <div class="row g-3">
                        <div class="col-md-6">
                            <x-input-label for="name" :value="__('Medicine Name')" />
                            <x-text-input id="name" class="block mt-1 w-full form-control" type="text"
                                name="name" :value="old('name', $medicine->name ?? '')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="col-md-6">
                            <x-input-label for="generic_name" :value="__('Generic Name')" />
                            <x-text-input id="generic_name" class="block mt-1 w-full form-control" type="text"
                                name="generic_name" :value="old('generic_name', $medicine->generic_name ?? '')" />
                            <x-input-error :messages="$errors->get('generic_name')" class="mt-2" />
                        </div>

                        <div class="col-md-6">
                            <x-input-label for="manufacturer" :value="__('Manufacturer')" />
                            <x-text-input id="manufacturer" class="block mt-1 w-full form-control" type="text"
                                name="manufacturer" :value="old('manufacturer', $medicine->manufacturer ?? '')" />
                            <x-input-error :messages="$errors->get('manufacturer')" class="mt-2" />
                        </div>

                        <div class="col-md-6">
                            <x-input-label for="dosage_form" :value="__('Dosage Form')" />
                            <select id="dosage_form" name="dosage_form" class="form-select mt-1 block w-full">
                                <option value="">Select Dosage Form</option>
                                @foreach (['Tablet', 'Capsule', 'Syrup', 'Injection', 'Ointment', 'Drops', 'Inhaler'] as $form)
                                    <option value="{{ $form }}"
                                        {{ old('dosage_form', $medicine->dosage_form ?? '') == $form ? 'selected' : '' }}>
                                        {{ $form }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('dosage_form')" class="mt-2" />
                        </div>

                        <div class="col-md-6">
                            <x-input-label for="strength" :value="__('Strength')" />
                            <x-text-input id="strength" class="block mt-1 w-full form-control" type="text"
                                name="strength" :value="old('strength', $medicine->strength ?? '')" placeholder="e.g. 500mg" />
                            <x-input-error :messages="$errors->get('strength')" class="mt-2" />
                        </div>

                        <div class="col-md-6">
                            <x-input-label for="price" :value="__('Unit Price')" />
                            <div class="input-group mt-1">
                                <span class="input-group-text">$</span>
                                <x-text-input id="price" class="form-control" type="number" step="0.01"
                                    name="price" :value="old('price', $medicine->price ?? '')" required />
                            </div>
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>

                        <div class="col-md-6">
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status" class="form-select mt-1 block w-full">
                                <option value="active"
                                    {{ old('status', $medicine->status ?? '') == 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="inactive"
                                    {{ old('status', $medicine->status ?? '') == 'inactive' ? 'selected' : '' }}>
                                    Inactive</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i>
                            {{ isset($medicine) ? 'Update Medicine' : 'Save Medicine' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
