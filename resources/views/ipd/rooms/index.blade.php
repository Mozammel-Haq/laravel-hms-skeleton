<x-app-layout>

    <div class="card mt-2 mx-2 p-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Rooms</h5>
            <a href="{{ route('ipd.rooms.create') }}" class="btn btn-primary">Add Room</a>
        </div>
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
