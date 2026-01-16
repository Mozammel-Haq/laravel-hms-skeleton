<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">Add Round Note</h4>
                <p class="text-muted mb-0">Record a doctor's visit for {{ $admission->patient->name }}</p>
            </div>
            <a href="{{ route('ipd.show', $admission->id) }}" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-1"></i> Back to Admission
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form method="POST" action="{{ route('ipd.rounds.store', $admission->id) }}">
                            @csrf
                            
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Date</label>
                                <input type="date" name="round_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Notes</label>
                                <textarea name="notes" class="form-control" rows="5" required placeholder="Enter clinical notes, observations, and instructions..."></textarea>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-device-floppy me-1"></i> Save Round Note
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
