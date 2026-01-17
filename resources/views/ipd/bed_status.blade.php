<x-app-layout>
    <div class="container-fluid">

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card mt-2">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="page-title mb-0">Bed Status</h3>
                            <a href="{{ route('ipd.index') }}" class="btn btn-outline-secondary">IPD Dashboard</a>
                        </div>
                        <div class="text-muted">Available Beds</div>
                        <div class="display-6">{{ $bedsAvailable }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-muted">Occupied Beds</div>
                        <div class="display-6">{{ $bedsOccupied }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Ward</th>
                                <th>Rooms</th>
                                <th>Total Beds</th>
                                <th>Available</th>
                                <th>Occupied</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($wards as $ward)
                                @php
                                    $beds = $ward->rooms->flatMap->beds;
                                    $total = $beds->count();
                                    $available = $beds->where('status', 'available')->count();
                                    $occupied = $beds->where('status', 'occupied')->count();
                                @endphp
                                <tr>
                                    <td>{{ $ward->name }}</td>
                                    <td>{{ $ward->rooms->count() }}</td>
                                    <td>{{ $total }}</td>
                                    <td><span class="badge bg-success">{{ $available }}</span></td>
                                    <td><span class="badge bg-danger">{{ $occupied }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
