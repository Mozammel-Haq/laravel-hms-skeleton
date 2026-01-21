<x-app-layout>

    <div class="card mt-2 mx-2 p-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Rooms</h5>
            <a href="{{ route('ipd.rooms.create') }}" class="btn btn-primary">Add Room</a>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('ipd.rooms.index') }}" class="mb-4">
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search Room Number..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available
                        </option>
                        <option value="occupied" {{ request('status') == 'occupied' ? 'selected' : '' }}>Occupied
                        </option>
                        <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>
                            Maintenance</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="from" class="form-control" placeholder="From Date"
                        value="{{ request('from') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="to" class="form-control" placeholder="To Date"
                        value="{{ request('to') }}">
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                    <a href="{{ route('ipd.rooms.index') }}" class="btn btn-light w-100">Reset</a>
                </div>
            </div>
        </form>

        <hr>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Room</th>
                        <th>Type</th>
                        <th>Daily Rate</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rooms as $room)
                        <tr>
                            <td>{{ $room->room_number }}</td>
                            <td>{{ $room->room_type }}</td>
                            <td>{{ number_format($room->daily_rate, 2) }}</td>
                            <td><span
                                    class="badge bg-{{ $room->status === 'available' ? 'success' : ($room->status === 'occupied' ? 'danger' : 'secondary') }}">{{ $room->status }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('ipd.rooms.edit', $room) }}"
                                    class="btn btn-sm btn-outline-secondary">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $rooms->links() }}
        </div>
    </div>
</x-app-layout>
