<x-app-layout>
    <div class="container-fluid m-2">

        <div class="card">
            <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-4 pb-3 pt-3">
            <div>
                <h4 class="font-bold mb-2 text-primary">Nursing Notes</h4>
                {{-- breadcrumb --}}
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-dots mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('nursing.notes.index') }}">Nursing</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Nursing Notes</li>
                    </ol>
                </nav>
            </div>
        </div>
        <hr>
                <form action="{{ route('nursing.notes.index') }}" method="GET" class="mb-4">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control"
                                placeholder="Search by patient name or code..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="admitted"
                                    {{ request('status', 'admitted') == 'admitted' ? 'selected' : '' }}>Admitted
                                </option>
                                <option value="discharged" {{ request('status') == 'discharged' ? 'selected' : '' }}>
                                    Discharged</option>
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="from" class="form-control" placeholder="From Date"
                                value="{{ request('from') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="to" class="form-control" placeholder="To Date"
                                value="{{ request('to') }}">
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                            <a href="{{ route('nursing.notes.index') }}" class="btn btn-light w-100">Reset</a>
                        </div>
                    </div>
                </form>

                <hr>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Note</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($admissions as $admission)
                                <tr>
                                    <td>{{ optional($admission->patient)->full_name ?? 'Patient' }}</td>
                                    <td><input type="text" class="form-control" placeholder="Add note"></td>
                                    <td class="text-end"><button type="button"
                                            class="btn btn-sm btn-primary">Save</button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $admissions->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
