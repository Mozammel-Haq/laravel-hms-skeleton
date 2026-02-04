<x-app-layout>
    <div class="container mx-1 mt-2">
        <div class="bg-primary-subtle text-primary px-4 py-2 pt-3 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="font-bold mb-2 text-primary">Create Room</h4>
                {{-- breadcrumb --}}
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('ipd.index') }}" class="text-decoration-none">IPD</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('ipd.rooms.index') }}" class="text-decoration-none">Rooms</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
                    </ol>
                </nav>
            </div>
        </div>
    <div class="card p-3">
        <div class="card-body">
            <form method="post" action="{{ route('ipd.rooms.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Ward</label>
                        <select name="ward_id" class="form-select" required>
                            @foreach ($wards as $ward)
                                <option value="{{ $ward->id }}">{{ $ward->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Room Number</label>
                        <input type="text" name="room_number" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Type</label>
                        <input type="text" name="room_type" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Daily Rate</label>
                        <input type="number" step="0.01" name="daily_rate" class="form-control" required>
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
                    <a href="{{ route('ipd.rooms.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
