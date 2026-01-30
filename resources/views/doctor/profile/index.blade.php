<x-app-layout>
    <div class="container-fluid">


        {{-- @if (session('success'))
            <div class="alert alert-success mb-3">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif --}}

        <div class="row g-3">
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">My Profile &amp; Portfolio</h5>
                        <a href="{{ route('doctors.show', $doctor) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-external-link-alt me-1"></i> Public View
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column align-items-center text-center mb-4">
                            <div class="position-relative mb-3">
                                @php
                                    $photoPath = $doctor->profile_photo;
                                @endphp
                                <img src="{{ $photoPath ? asset($photoPath) : asset('assets/img/doctors/doctor-01.jpg') }}"
                                    alt="{{ $doctor->user?->name }}"
                                    class="rounded-circle border border-3 border-light shadow-sm"
                                    style="width: 120px; height: 120px; object-fit: cover;">
                            </div>
                            <h5 class="mb-1">{{ $doctor->user?->name }}</h5>
                            <p class="text-muted mb-2">{{ $doctor->department?->name ?? 'No Department' }}</p>
                            <div class="badge bg-soft-primary text-primary rounded-pill px-3 py-2">
                                @php
                                    $specData = $doctor->specialization;
                                    $specData = \Illuminate\Support\Arr::wrap($specData);
                                    $finalSpecs = [];
                                    foreach ($specData as $item) {
                                        if (is_string($item)) {
                                            $decoded = json_decode($item, true);
                                            if (json_last_error() === JSON_ERROR_NONE) {
                                                if (is_array($decoded)) {
                                                    foreach (\Illuminate\Support\Arr::flatten($decoded) as $sub) {
                                                        $finalSpecs[] = $sub;
                                                    }
                                                } else {
                                                    $finalSpecs[] = $decoded;
                                                }
                                            } else {
                                                $finalSpecs[] = $item;
                                            }
                                        } elseif (is_array($item)) {
                                            foreach (\Illuminate\Support\Arr::flatten($item) as $sub) {
                                                $finalSpecs[] = $sub;
                                            }
                                        } else {
                                            $finalSpecs[] = $item;
                                        }
                                    }
                                    $pieces = [];
                                    foreach (\Illuminate\Support\Arr::flatten($finalSpecs) as $s) {
                                        if (is_string($s)) {
                                            foreach (explode(',', $s) as $part) {
                                                $t = trim($part, " \t\n\r\0\x0B\"'[]");
                                                if ($t !== '') {
                                                    $pieces[] = $t;
                                                }
                                            }
                                        }
                                    }
                                    $pieces = array_slice($pieces, 0, 2);
                                @endphp
                                {{ empty($pieces) ? 'General Physician' : implode(', ', $pieces) }}
                            </div>
                        </div>

                        <div class="border-top pt-3">
                            <div class="row text-center">
                                <div class="col-6 border-end">
                                    <p class="text-muted small mb-1">Consultation</p>
                                    <h6 class="mb-0 text-primary">
                                        {{ !is_null($doctor->consultation_fee) ? number_format($doctor->consultation_fee, 2) : 'N/A' }}
                                    </h6>
                                </div>
                                <div class="col-6">
                                    <p class="text-muted small mb-1">Follow Up</p>
                                    <h6 class="mb-0 text-primary">
                                        {{ !is_null($doctor->follow_up_fee) ? number_format($doctor->follow_up_fee, 2) : 'N/A' }}
                                    </h6>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h6 class="text-uppercase text-muted fs-12 fw-bold mb-2">Biography</h6>
                            <p class="text-muted fs-14 mb-0" style="text-align: justify;">
                                {{ $doctor->biography ?: 'No biography available for this doctor.' }}
                            </p>
                        </div>

                        <div class="mt-4 d-grid">
                            <a href="{{ route('profile.edit') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-cog me-1"></i> Edit Account Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="row g-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Education</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('doctor.profile.educations.store') }}"
                                    class="row g-2 align-items-end mb-3">
                                    @csrf
                                    <div class="col-md-4">
                                        <label class="form-label">Degree</label>
                                        <input type="text" name="degree" class="form-control"
                                            value="{{ old('degree') }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Institution</label>
                                        <input type="text" name="institution" class="form-control"
                                            value="{{ old('institution') }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Country</label>
                                        <input type="text" name="country" class="form-control"
                                            value="{{ old('country') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Start Year</label>
                                        <input type="number" name="start_year" class="form-control"
                                            value="{{ old('start_year') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">End Year</label>
                                        <input type="number" name="end_year" class="form-control"
                                            value="{{ old('end_year') }}">
                                    </div>
                                    <div class="col-md-3 ms-auto text-end">
                                        <button type="submit" class="btn btn-primary mt-4">Add Education</button>
                                    </div>
                                </form>

                                @if ($doctor->educations->isEmpty())
                                    <div class="text-muted fs-13">No education records added yet.</div>
                                @else
                                    @foreach ($doctor->educations as $education)
                                        <div class="border rounded p-2 mb-2">
                                            <div class="row g-2 align-items-end">
                                                <div class="col-md-9">
                                                    <form method="POST"
                                                        action="{{ route('doctor.profile.educations.update', $education) }}"
                                                        class="row g-2 align-items-end">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="col-md-4">
                                                            <input type="text" name="degree" class="form-control"
                                                                value="{{ old('degree_' . $education->id, $education->degree) }}"
                                                                required>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input type="text" name="institution"
                                                                class="form-control"
                                                                value="{{ old('institution_' . $education->id, $education->institution) }}"
                                                                required>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input type="text" name="country" class="form-control"
                                                                value="{{ old('country_' . $education->id, $education->country) }}"
                                                                placeholder="Country">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="number" name="start_year"
                                                                class="form-control"
                                                                value="{{ old('start_year_' . $education->id, $education->start_year) }}"
                                                                placeholder="Start year">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="number" name="end_year"
                                                                class="form-control"
                                                                value="{{ old('end_year_' . $education->id, $education->end_year) }}"
                                                                placeholder="End year">
                                                        </div>
                                                        <div class="col-md-6 d-flex justify-content-end">
                                                            <button type="submit"
                                                                class="btn btn-sm btn-outline-primary">Save</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="col-md-3 d-flex justify-content-end mt-2 mt-md-0">
                                                    <form method="POST"
                                                        action="{{ route('doctor.profile.educations.destroy', $education) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-outline-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Awards</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('doctor.profile.awards.store') }}"
                                    class="row g-2 align-items-end mb-3">
                                    @csrf
                                    <div class="col-md-7">
                                        <label class="form-label">Title</label>
                                        <input type="text" name="title" class="form-control"
                                            value="{{ old('title') }}" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Year</label>
                                        <input type="number" name="year" class="form-control"
                                            value="{{ old('year') }}">
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <button type="submit" class="btn btn-primary mt-4">Add</button>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label mt-2">Description</label>
                                        <textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea>
                                    </div>
                                </form>

                                @if ($doctor->awards->isEmpty())
                                    <div class="text-muted fs-13">No awards added yet.</div>
                                @else
                                    @foreach ($doctor->awards as $award)
                                        <div class="border rounded p-2 mb-2">
                                            <div class="row g-2 align-items-end">
                                                <div class="col-md-9">
                                                    <form method="POST"
                                                        action="{{ route('doctor.profile.awards.update', $award) }}"
                                                        class="row g-2 align-items-end">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="col-md-7">
                                                            <input type="text" name="title" class="form-control"
                                                                value="{{ old('title_' . $award->id, $award->title) }}"
                                                                required>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="number" name="year" class="form-control"
                                                                value="{{ old('year_' . $award->id, $award->year) }}"
                                                                placeholder="Year">
                                                        </div>
                                                        <div class="col-md-2 text-end">
                                                            <button type="submit"
                                                                class="btn btn-sm btn-outline-primary">Save</button>
                                                        </div>
                                                        <div class="col-12 mt-2">
                                                            <textarea name="description" class="form-control" rows="2">{{ old('description_' . $award->id, $award->description) }}</textarea>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="col-md-3 d-flex justify-content-end mt-2 mt-md-0">
                                                    <form method="POST"
                                                        action="{{ route('doctor.profile.awards.destroy', $award) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-outline-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Certifications</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('doctor.profile.certifications.store') }}"
                                    class="row g-2 align-items-end mb-3">
                                    @csrf
                                    <div class="col-md-6">
                                        <label class="form-label">Title</label>
                                        <input type="text" name="title" class="form-control"
                                            value="{{ old('title') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Issued By</label>
                                        <input type="text" name="issued_by" class="form-control"
                                            value="{{ old('issued_by') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Issued Date</label>
                                        <input type="date" name="issued_date" class="form-control"
                                            value="{{ old('issued_date') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Expiry Date</label>
                                        <input type="date" name="expiry_date" class="form-control"
                                            value="{{ old('expiry_date') }}">
                                    </div>
                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-primary mt-3">Add Certification</button>
                                    </div>
                                </form>

                                @if ($doctor->certifications->isEmpty())
                                    <div class="text-muted fs-13">No certifications added yet.</div>
                                @else
                                    @foreach ($doctor->certifications as $certification)
                                        <div class="border rounded p-2 mb-2">
                                            <div class="row g-2 align-items-end">
                                                <div class="col-md-9">
                                                    <form method="POST"
                                                        action="{{ route('doctor.profile.certifications.update', $certification) }}"
                                                        class="row g-2 align-items-end">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="col-md-6">
                                                            <input type="text" name="title" class="form-control"
                                                                value="{{ old('title_' . $certification->id, $certification->title) }}"
                                                                required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" name="issued_by"
                                                                class="form-control"
                                                                value="{{ old('issued_by_' . $certification->id, $certification->issued_by) }}"
                                                                placeholder="Issued by">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="date" name="issued_date"
                                                                class="form-control"
                                                                value="{{ old('issued_date_' . $certification->id, $certification->issued_date) }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="date" name="expiry_date"
                                                                class="form-control"
                                                                value="{{ old('expiry_date_' . $certification->id, $certification->expiry_date) }}">
                                                        </div>
                                                        <div class="col-12 d-flex justify-content-start mt-2">
                                                            <button type="submit"
                                                                class="btn btn-sm btn-outline-primary">Save</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="col-md-3 d-flex justify-content-end mt-2 mt-md-0">
                                                    <form method="POST"
                                                        action="{{ route('doctor.profile.certifications.destroy', $certification) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-outline-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
