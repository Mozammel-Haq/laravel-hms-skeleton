<x-app-layout>
    <div class="container-fluid mx-2 mt-2">
        <div class="card shadow-sm border-0">


            <div class="card-body p-3">
                            <!-- Header -->
            <div class="d-flex mb-2 justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-4 pb-3 rounded-top">
                <div>
                    <h4 class="fw-bold text-primary mb-2">Edit Bed</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-dots mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('ipd.index') }}">IPD</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('ipd.beds.index') }}">Beds</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('ipd.beds.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="ti ti-arrow-left me-1"></i>Back
                </a>
            </div>
            <hr>
                <h5 class="card-title mb-4 pb-2 border-bottom">Bed Information</h5>

                <form method="post" action="{{ route('ipd.beds.update', $bed) }}">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Room <span class="text-danger">*</span></label>
                            <select name="room_id" class="form-select form-select-sm" required>
                                <option value="">Select Room</option>
                                @foreach ($rooms as $room)
                                    <option value="{{ $room->id }}" @selected($bed->room_id === $room->id)>
                                        {{ $room->room_number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Bed Number <span class="text-danger">*</span></label>
                            <input type="text" name="bed_number" class="form-control form-control-sm" value="{{ $bed->bed_number }}"
                                required placeholder="e.g. B-101">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select form-select-sm" required>
                                <option value="available" @selected($bed->status === 'available')>Available</option>
                                <option value="occupied" @selected($bed->status === 'occupied')>Occupied</option>
                                <option value="maintenance" @selected($bed->status === 'maintenance')>Maintenance</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-top d-flex gap-2 justify-content-end">
                        <a href="{{ route('ipd.beds.index') }}" class="btn btn-light btn-sm">Cancel</a>
                        <button type="submit" class="btn btn-primary btn-sm px-4">Update Bed</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
