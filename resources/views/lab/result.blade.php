<x-app-layout>
    <div class="container-fluid m-2">
        <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 py-2 pt-3">
            <div>
                <h4 class="font-bold mb-2 text-primary">Record Lab Result</h4>
                {{-- breadcrumb --}}
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-dots mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('lab.index') }}">Lab Tests</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Record Result</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="card p-2">
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
