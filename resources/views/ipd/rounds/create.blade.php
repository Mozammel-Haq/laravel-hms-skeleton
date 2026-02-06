<x-app-layout>
    <div class="container-fluid mx-2">
        <div class="row justify-content-center px-2">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mt-2">
                    <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-4 pb-3 rounded-top">
                        <div>
                            <h4 class="font-bold mb-2 text-primary">Add Round Note</h4>
                            {{-- breadcrumb --}}
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-dots mb-0">
                                    <li class="breadcrumb-item"><a href="{{ route('ipd.index') }}">IPD</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('ipd.show', $admission->id) }}">{{ $admission->patient->name }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Add Round Note</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('ipd.rounds.store', $admission->id) }}">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Date</label>
                                <input type="date" name="round_date" class="form-control" value="{{ date('Y-m-d') }}"
                                    required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Notes</label>
                                <textarea name="notes" class="form-control" rows="5" required
                                    placeholder="Enter clinical notes, observations, and instructions..."></textarea>
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
