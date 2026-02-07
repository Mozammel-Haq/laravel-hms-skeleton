<x-app-layout>
    <div class="container-fluid mx-2 mt-2">

        <div class="card shadow-sm rounded-bottom mt-0 border-0">
            <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-4 pb-3 rounded-top shadow-sm mb-0">
            <div>
                <h5 class="fw-bold mb-1 text-primary">Record Lab Result</h5>
                {{-- breadcrumb --}}
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-dots mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('lab.index') }}">Lab Tests</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Record Result</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('lab.index') }}" class="btn btn-sm btn-outline-primary">
                <i class="ti ti-arrow-left me-1"></i> Back to List
            </a>
        </div>
        <hr>
                <form method="post" action="{{ route('lab.result.store', $order) }}" enctype="multipart/form-data">
                    @csrf

                    <h5 class="card-title mb-4 pb-2 border-bottom">Result Details</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Result Value <span class="text-danger">*</span></label>
                            <input type="text" name="result_value" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Notes</label>
                            <input type="text" name="notes" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small text-muted">Upload Report (PDF, max 5MB)</label>
                            <div class="input-group input-group-sm">
                                <input type="file" name="report_pdf" accept="application/pdf" class="form-control form-control-sm" id="reportPdf">
                                <label class="input-group-text" for="reportPdf">Upload</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('lab.index') }}" class="btn btn-light btn-sm">Cancel</a>
                        <button type="submit" class="btn btn-primary btn-sm">Save Result</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
