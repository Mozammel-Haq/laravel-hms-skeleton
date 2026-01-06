<x-app-layout>
    <h5 class="mb-3">Edit Bed</h5>
    <div class="card">
        <div class="card-body">
            <form method="post" action="{{ route('ipd.beds.update', $bed) }}">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Room</label>
                        <select name="room_id" class="form-select" required>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" @if($bed->room_id===$room->id) selected @endif>{{ $room->room_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Bed Number</label>
                        <input type="text" name="bed_number" class="form-control" value="{{ $bed->bed_number }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="available" @if($bed->status==='available') selected @endif>Available</option>
                            <option value="occupied" @if($bed->status==='occupied') selected @endif>Occupied</option>
                            <option value="maintenance" @if($bed->status==='maintenance') selected @endif>Maintenance</option>
                        </select>
                    </div>
                </div>
                <div class="mt-3 d-flex gap-2">
                    <button class="btn btn-primary">Update</button>
                    <a href="{{ route('ipd.beds.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
