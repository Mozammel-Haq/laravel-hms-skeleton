<x-app-layout>
    <div class="page-header d-flex justify-content-between align-items-center mb-4 card p-4 mt-2">
        <div class="page-title">
            <h4>Start Visit</h4>
            <p class="text-muted">Create a new visit for a patient</p>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('visits.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Select Appointment</label>
                    <select name="appointment_id" class="form-select" required>
                        <option value="">Choose an appointment</option>
                        @foreach ($appointments as $a)
                            <option value="{{ $a->id }}">
                                #{{ $a->id }} — {{ optional($a->patient)->name }} with
                                {{ optional($a->doctor)->user->name ?? 'Doctor' }} on {{ $a->appointment_date }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Start Visit</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
