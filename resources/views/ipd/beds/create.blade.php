<x-app-layout>

    <div class="card mt-2">
        <div class="card-body">
            <h5 class="mb-3">Create Bed</h5>
            <form method="post" action="{{ route('ipd.beds.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Room</label>
                        <select name="room_id" class="form-select" required>
                            @foreach ($rooms as $room)
                                <option value="{{ $room->id }}">{{ $room->room_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Bed Number</label>
                        <input type="text" name="bed_number" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="available">Available</option>
                            <option value="occupied">Occupied</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>
                </div>
                <div class="mt-3 d-flex gap-2">
                    <button class="btn btn-primary">Save</button>
                    <a href="{{ route('ipd.beds.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
