<x-app-layout>
                    <div class="content pb-0">
    <x-slot name="header">
        <h2 class="h4">Edit Clinic</h2>
    </x-slot>
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

            <form method="POST" action="{{ route('clinics.update', $clinic) }}">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input name="name" class="form-control" value="{{ old('name', $clinic->name) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Code</label>
                        <input name="code" class="form-control" value="{{ old('code', $clinic->code) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Registration No.</label>
                        <input name="registration_number" class="form-control" value="{{ old('registration_number', $clinic->registration_number) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Address Line 1</label>
                        <input name="address_line_1" class="form-control" value="{{ old('address_line_1', $clinic->address_line_1) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Address Line 2</label>
                        <input name="address_line_2" class="form-control" value="{{ old('address_line_2', $clinic->address_line_2) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">City</label>
                        <input name="city" class="form-control" value="{{ old('city', $clinic->city) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">State</label>
                        <input name="state" class="form-control" value="{{ old('state', $clinic->state) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Country</label>
                        <input name="country" class="form-control" value="{{ old('country', $clinic->country) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Postal Code</label>
                        <input name="postal_code" class="form-control" value="{{ old('postal_code', $clinic->postal_code) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Phone</label>
                        <input name="phone" class="form-control" value="{{ old('phone', $clinic->phone) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Email</label>
                        <input name="email" type="email" class="form-control" value="{{ old('email', $clinic->email) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Website</label>
                        <input name="website" type="url" class="form-control" value="{{ old('website', $clinic->website) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Timezone</label>
                        <input name="timezone" class="form-control" value="{{ old('timezone', $clinic->timezone) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Currency</label>
                        <input name="currency" class="form-control" value="{{ old('currency', $clinic->currency) }}" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Opening Time</label>
                        <input name="opening_time" type="time" class="form-control" value="{{ old('opening_time', $clinic->opening_time) }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Closing Time</label>
                        <input name="closing_time" type="time" class="form-control" value="{{ old('closing_time', $clinic->closing_time) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="active" {{ old('status', $clinic->status)==='active'?'selected':'' }}>Active</option>
                            <option value="inactive" {{ old('status', $clinic->status)==='inactive'?'selected':'' }}>Inactive</option>
                            <option value="suspended" {{ old('status', $clinic->status)==='suspended'?'selected':'' }}>Suspended</option>
                        </select>
                    </div>
                </div>
                <div class="mt-3">
                    <button class="btn btn-primary" type="submit">Update</button>
                    <a class="btn btn-secondary" href="{{ route('clinics.index') }}">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    </div>
</x-app-layout>
