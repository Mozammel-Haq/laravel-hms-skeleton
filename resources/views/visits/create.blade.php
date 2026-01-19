<x-app-layout>
    <div class="card mx-2 p-2 mt-2">
        <div class="card-body">
                <div class="page-header d-flex justify-content-between align-items-center">
        <div class="page-title">
            <h4>Start Visit</h4>
            <p class="text-muted">Create a new visit for a patient</p>
        </div>
    </div>
    <hr class="my-4">
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
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Discount</label>
                        <input type="number" name="discount" class="form-control" step="0.01" min="0" value="0">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tax (%)</label>
                        <input type="number" name="tax" class="form-control" step="0.01" min="0" value="0">
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Start Visit</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
