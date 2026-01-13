<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="page-title mb-0">Record Lab Result</h3>
            <a href="{{ route('lab.show', $order) }}" class="btn btn-outline-secondary">Back</a>
        </div>
        <div class="card">
            <div class="card-body">
                <form method="post" action="{{ route('lab.result.store', $order) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Result Value</label>
                            <input type="text" name="result_value" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Notes</label>
                            <input type="text" name="notes" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Upload Report (PDF, max 5MB)</label>
                            <input type="file" name="report_pdf" accept="application/pdf" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
