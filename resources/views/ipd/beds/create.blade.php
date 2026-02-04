<x-app-layout>
    <div class="card mt-2 mx-2 p-3">
        <div class="bg-primary-subtle text-primary px-4 py-2 pt-3">
            <h5 class="mb-0">Create Bed</h5>
            {{-- breadcrumb --}}
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('ipd.index') }}" class="text-decoration-none">IPD</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('ipd.beds.index') }}" class="text-decoration-none">Beds</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
        <hr>

        <div class="card-body">
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
