<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="page-title mb-0">Create Prescription</h3>
            <a href="{{ route('clinical.consultations.show', $consultation->id) }}" class="btn btn-outline-secondary">Back to Consultation</a>
        </div>
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('clinical.prescriptions.store', $consultation->id) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <h5 class="mb-2">Prescription Items</h5>
                        <div id="items">
                            @for ($i = 0; $i < 3; $i++)
                                <div class="row g-2 align-items-end mb-2">
                                    <div class="col-md-4">
                                        <label class="form-label">Medicine</label>
                                        <select name="items[{{ $i }}][medicine_id]" class="form-select">
                                            <option value="">Select medicine</option>
                                            @foreach ($medicines as $med)
                                                <option value="{{ $med->id }}">{{ $med->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Dosage</label>
                                        <input type="text" name="items[{{ $i }}][dosage]" class="form-control" placeholder="e.g., 1-0-1">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Frequency</label>
                                        <input type="text" name="items[{{ $i }}][frequency]" class="form-control" placeholder="e.g., BID">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Duration (days)</label>
                                        <input type="number" name="items[{{ $i }}][duration_days]" class="form-control" min="1" value="5">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Instructions</label>
                                        <input type="text" name="items[{{ $i }}][instructions]" class="form-control" placeholder="After food">
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Save Prescription</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
