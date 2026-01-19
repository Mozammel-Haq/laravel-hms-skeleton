<table>
    <thead>
        <tr>
            <th colspan="2" style="font-weight: bold; font-size: 14px;">Executive Summary</th>
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
            <th colspan="2" style="font-weight: bold;">Key Performance Indicators</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Total Patients</td>
            <td>{{ $patientsTotal }}</td>
        </tr>
        <tr>
            <td>New Patients (Period)</td>
            <td>{{ $newPatientsTotal }}</td>
        </tr>
        <tr>
            <td>Total Admissions</td>
            <td>{{ $admissionsTotal }}</td>
        </tr>
        <tr>
            <td>Total Invoiced</td>
            <td>{{ number_format($invoicesTotal, 2) }}</td>
        </tr>
        <tr>
            <td>Total Collected</td>
            <td>{{ number_format($paymentsTotal, 2) }}</td>
        </tr>
    </tbody>
</table>

<br>

<table>
    <thead>
        <tr>
            <th colspan="2" style="font-weight: bold;">Appointment Status Distribution</th>
        </tr>
        <tr>
            <th>Status</th>
            <th>Count</th>
        </tr>
    </thead>
    <tbody>
        @foreach($appointmentStats as $status => $count)
            <tr>
                <td>{{ ucfirst($status) }}</td>
                <td>{{ $count }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<br>

<table>
    <thead>
        <tr>
            <th colspan="5" style="font-weight: bold;">Recent Admissions</th>
        </tr>
        <tr>
            <th>Patient</th>
            <th>Doctor</th>
            <th>Admission Date</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($admissions as $admission)
            <tr>
                <td>{{ $admission->patient->user->name ?? 'N/A' }}</td>
                <td>{{ $admission->doctor->user->name ?? 'N/A' }}</td>
                <td>{{ $admission->created_at }}</td>
                <td>{{ ucfirst($admission->status) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
