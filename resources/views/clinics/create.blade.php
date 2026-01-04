<x-app-layout>
    <x-slot name="header">
        <h2 class="h4">Create Clinic</h2>
    </x-slot>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('clinics.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Code</label>
                        <input name="code" class="form-control" value="{{ old('code') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Registration No.</label>
                        <input name="registration_number" class="form-control" value="{{ old('registration_number') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Address Line 1</label>
                        <input name="address_line_1" class="form-control" value="{{ old('address_line_1') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Address Line 2</label>
                        <input name="address_line_2" class="form-control" value="{{ old('address_line_2') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">City</label>
                        <input name="city" class="form-control" value="{{ old('city') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">State</label>
                        <input name="state" class="form-control" value="{{ old('state') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Country</label>
                        <input name="country" class="form-control" value="{{ old('country') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Postal Code</label>
                        <input name="postal_code" class="form-control" value="{{ old('postal_code') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Phone</label>
                        <input name="phone" class="form-control" value="{{ old('phone') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Email</label>
                        <input name="email" type="email" class="form-control" value="{{ old('email') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Website</label>
                        <input name="website" type="url" class="form-control" value="{{ old('website') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Timezone</label>
                        <input name="timezone" class="form-control" value="{{ old('timezone','UTC') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Currency</label>
                        <input name="currency" class="form-control" value="{{ old('currency','USD') }}" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Opening Time</label>
                        <input name="opening_time" type="time" class="form-control" value="{{ old('opening_time') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Closing Time</label>
                        <input name="closing_time" type="time" class="form-control" value="{{ old('closing_time') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="active" {{ old('status')==='active'?'selected':'' }}>Active</option>
                            <option value="inactive" {{ old('status')==='inactive'?'selected':'' }}>Inactive</option>
                            <option value="suspended" {{ old('status')==='suspended'?'selected':'' }}>Suspended</option>
                        </select>
                    </div>
                </div>
                <div class="mt-3">
                    <button class="btn btn-primary" type="submit">Save</button>
                    <a class="btn btn-secondary" href="{{ route('clinics.index') }}">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
