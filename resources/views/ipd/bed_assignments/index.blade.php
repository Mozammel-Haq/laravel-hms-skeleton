<x-app-layout>

    <div class="card m-2 p-3">
        <h5 class="mb-3">Bed Assignments</h5>
        <div class="table-responsive">
            <table class="table table-hover mb-0 datatable">
                <thead>
                    <tr>
                        <th>Bed</th>
                        <th>Patient</th>
                        <th>Assigned At</th>
                        <th>Released At</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($assignments as $assignment)
                        <tr>
                            <td>{{ optional($assignment->bed)->bed_number }}</td>
                            <td>{{ optional($assignment->admission->patient)->name }}</td>
                            <td>{{ $assignment->assigned_at }}</td>
                            <td>{{ $assignment->released_at }}</td>
                            <td class="text-end">
                                <a href="{{ route('ipd.bed_assignments.show', $assignment) }}"
                                    class="btn btn-sm btn-outline-primary">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $assignments->links() }}
        </div>
    </div>
</x-app-layout>
