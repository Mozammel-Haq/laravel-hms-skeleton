<x-app-layout>
    <div class="container-fluid">

        <div class="card mt-3 mx-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="page-title mb-0">Order Lab Test</h3>
                    <a href="{{ route('lab.index') }}" class="btn btn-outline-secondary">Back</a>
                </div>
                <hr>
                <form method="post" action="{{ route('lab.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Patient</label>
                            <select name="patient_id" class="form-select" required>
                                <option value="">Select patient</option>
                                @foreach ($patients as $p)
                                    <option value="{{ $p->id }}">{{ $p->full_name ?? ($p->name ?? 'Patient') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Test</label>
                            <select name="lab_test_id" class="form-select" required>
                                <option value="">Select test</option>
                                @foreach ($tests as $t)
                                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Doctor (optional)</label>
                            <input type="number" name="doctor_id" class="form-control" placeholder="Doctor ID">
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
