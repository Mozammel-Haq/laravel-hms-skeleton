<x-app-layout>
    <h5 class="mb-3">Start Visit</h5>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('visits.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Select Appointment</label>
                    <select name="appointment_id" class="form-select" required>
                        <option value="">Choose an appointment</option>
                        @foreach($appointments as $a)
                            <option value="{{ $a->id }}">
                                #{{ $a->id }} — {{ optional($a->patient)->name }} with {{ optional($a->doctor)->user->name ?? 'Doctor' }} on {{ $a->appointment_date }}
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
