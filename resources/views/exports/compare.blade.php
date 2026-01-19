<table>
    <thead>
        <tr>
            <th colspan="{{ count($selectedClinics) + 1 }}" style="font-weight: bold; font-size: 14px;">Clinic Comparison Report</th>
        </tr>
        <tr>
            <th>Period</th>
            <td colspan="{{ count($selectedClinics) }}">{{ $startDate->format('Y-m-d') }} to {{ $endDate->format('Y-m-d') }}</td>
        </tr>
        <tr></tr>
        <tr>
            <th style="font-weight: bold;">Metric</th>
            @foreach ($selectedClinics as $clinic)
                <th style="font-weight: bold;">{{ $clinic->name }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Total Revenue</td>
            @foreach ($selectedClinics as $clinic)
                <td>{{ number_format($stats[$clinic->id]['revenue'], 2) }}</td>
            @endforeach
        </tr>
        <tr>
            <td>Total Patients</td>
            @foreach ($selectedClinics as $clinic)
                <td>{{ $stats[$clinic->id]['patients'] }}</td>
            @endforeach
        </tr>
        <tr>
            <td>Total Appointments</td>
            @foreach ($selectedClinics as $clinic)
                <td>{{ $stats[$clinic->id]['appointments'] }}</td>
            @endforeach
        </tr>
        <tr>
            <td>Staff Count</td>
            @foreach ($selectedClinics as $clinic)
                <td>{{ $stats[$clinic->id]['staff'] }}</td>
            @endforeach
        </tr>
    </tbody>
</table>
