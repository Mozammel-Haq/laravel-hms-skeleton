<x-app-layout>

    <div class="card mt-2 mx-2 p-3">
        <div class="card-body">
            <h5 class="mb-3">Edit Room</h5>
            <hr>
            <form method="post" action="{{ route('ipd.rooms.update', $room) }}">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Ward</label>
                        <select name="ward_id" class="form-select" required>
                            @foreach ($wards as $ward)
                                <option value="{{ $ward->id }}" @if ($room->ward_id === $ward->id) selected @endif>
                                    {{ $ward->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Room Number</label>
                        <input type="text" name="room_number" class="form-control" value="{{ $room->room_number }}"
                            required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Type</label>
                        <input type="text" name="room_type" class="form-control" value="{{ $room->room_type }}"
                            required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Daily Rate</label>
                        <input type="number" step="0.01" name="daily_rate" class="form-control"
                            value="{{ $room->daily_rate }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="available" @if ($room->status === 'available') selected @endif>Available
                            </option>
                            <option value="occupied" @if ($room->status === 'occupied') selected @endif>Occupied</option>
                            <option value="maintenance" @if ($room->status === 'maintenance') selected @endif>Maintenance
                            </option>
                        </select>
                    </div>
                </div>
                <div class="mt-3 d-flex gap-2">
                    <button class="btn btn-primary">Update</button>
                    <a href="{{ route('ipd.rooms.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
