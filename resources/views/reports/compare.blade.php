<x-app-layout>
    <div class="page-header d-flex justify-content-between align-items-center">
        <div class="page-title">
            <h4>Clinic Comparison Report</h4>
            <p>Comparing {{ $clinics->count() }} Clinics</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-center mb-0">
                            <thead>
                                <tr>
                                    <th>Metric</th>
                                    @foreach($clinics as $clinic)
                                        <th class="text-center">{{ $clinic->name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fw-bold">Total Patients</td>
                                    @foreach($clinics as $clinic)
                                        <td class="text-center">{{ number_format($stats[$clinic->id]['patients']) }}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="fw-bold">Total Appointments</td>
                                    @foreach($clinics as $clinic)
                                        <td class="text-center">{{ number_format($stats[$clinic->id]['appointments']) }}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="fw-bold">Total Revenue</td>
                                    @foreach($clinics as $clinic)
                                        <td class="text-center text-success">${{ number_format($stats[$clinic->id]['revenue'], 2) }}</td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
