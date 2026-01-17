<x-app-layout>
    <div class="container-fluid">

        <div class="card mt-3">

            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="page-title mb-0">Doctor Performance</h3>
                    <a href="{{ route('reports.summary') }}" class="btn btn-outline-secondary">Summary</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Doctor</th>
                                <th>Consultations</th>
                                <th>Admissions</th>
                                <th>Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topDoctors as $row)
                                <tr>
                                    <td>{{ $row['doctor']->user?->name }}</td>
                                    <td>{{ $row['consults'] }}</td>
                                    <td>{{ $row['admissions'] }}</td>
                                    <td><span class="badge bg-primary">{{ $row['score'] }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
