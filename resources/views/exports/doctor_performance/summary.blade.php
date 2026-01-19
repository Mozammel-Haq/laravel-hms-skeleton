<table>
    <thead>
        <tr>
            <th colspan="2" style="font-weight: bold; font-size: 14px;">Doctor Performance Report</th>
        </tr>
        <tr>
            <th>Period</th>
            <td>{{ $startDate->format('Y-m-d') }} to {{ $endDate->format('Y-m-d') }}</td>
        </tr>
    </thead>
</table>

<br>

<table>
    <thead>
        <tr>
            <th colspan="6" style="font-weight: bold;">Performance Metrics</th>
        </tr>
        <tr>
            <th>Rank</th>
            <th>Doctor</th>
            <th>Department</th>
            <th>Consultations</th>
            <th>Admissions</th>
            <th>Revenue Generated</th>
        </tr>
    </thead>
    <tbody>
        @foreach($topDoctors as $index => $doctor)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $doctor['name'] }}</td>
                <td>{{ $doctor['department'] }}</td>
                <td>{{ $doctor['consults'] }}</td>
                <td>{{ $doctor['admissions'] }}</td>
                <td>{{ number_format($doctor['revenue'], 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
