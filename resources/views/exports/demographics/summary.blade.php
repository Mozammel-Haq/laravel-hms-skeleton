<table>
    <thead>
        <tr>
            <th colspan="2" style="font-weight: bold; font-size: 14px;">Patient Demographics Report</th>
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
            <th colspan="2" style="font-weight: bold;">Gender Distribution</th>
        </tr>
        <tr>
            <th>Gender</th>
            <th>Count</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($genderStats as $gender => $count)
            <tr>
                <td>{{ ucfirst($gender) }}</td>
                <td>{{ $count }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<br>

<table>
    <thead>
        <tr>
            <th colspan="2" style="font-weight: bold;">Age Groups</th>
        </tr>
        <tr>
            <th>Age Group</th>
            <th>Count</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($ageGroups as $group => $count)
            <tr>
                <td>{{ $group }}</td>
                <td>{{ $count }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
