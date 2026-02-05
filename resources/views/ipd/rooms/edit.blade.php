<x-app-layout>
    <div class="container-fluid mx-2 mt-2">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-4 pb-3 rounded-top shadow-sm mb-0">
            <div>
                <h4 class="mb-1 fw-bold text-dark">Edit Room</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-dots mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('ipd.index') }}">IPD</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('ipd.rooms.index') }}">Rooms</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('ipd.rooms.index') }}" class="btn btn-secondary btn-sm">
                <i class="ti ti-arrow-left me-1"></i>Back
            </a>
        </div>

        <div class="card shadow-sm border-0 rounded-bottom mt-0">
            <div class="card-body p-3">
                <h5 class="card-title mb-4 pb-2 border-bottom">Room Information</h5>

                <form method="post" action="{{ route('ipd.rooms.update', $room) }}">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Ward <span class="text-danger">*</span></label>
                            <select name="ward_id" class="form-select form-select-sm" required>
                                <option value="">Select Ward</option>
                                @foreach ($wards as $ward)
                                    <option value="{{ $ward->id }}" @selected($room->ward_id === $ward->id)>
                                        {{ $ward->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Room Number <span class="text-danger">*</span></label>
                            <input type="text" name="room_number" class="form-control form-control-sm" value="{{ $room->room_number }}"
                                required placeholder="e.g. 101">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Type <span class="text-danger">*</span></label>
                            <input type="text" name="room_type" class="form-control form-control-sm" value="{{ $room->room_type }}"
                                required placeholder="e.g. Private, Semi-Private">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Daily Rate <span class="text-danger">*</span></label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" name="daily_rate" class="form-control"
                                    value="{{ $room->daily_rate }}" required placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select form-select-sm" required>
                                <option value="available" @selected($room->status === 'available')>Available</option>
                                <option value="occupied" @selected($room->status === 'occupied')>Occupied</option>
                                <option value="maintenance" @selected($room->status === 'maintenance')>Maintenance</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-top d-flex gap-2 justify-content-end">
                        <a href="{{ route('ipd.rooms.index') }}" class="btn btn-light btn-sm">Cancel</a>
                        <button type="submit" class="btn btn-primary btn-sm px-4">Update Room</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
