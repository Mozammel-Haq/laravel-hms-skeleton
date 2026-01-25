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
                <div class="card mt-2">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="page-title mb-0">My Profile &amp; Portfolio</h3>
                            <a href="{{ route('doctors.show', $doctor) }}" class="btn btn-outline-secondary">View Public
                                Profile</a>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <span class="avatar avatar-xl rounded-circle me-3">
                                @php
                                    $photoPath = $doctor->profile_photo;
                                @endphp
                                <img src="{{ $photoPath ? asset($photoPath) : asset('assets/img/doctors/doctor-01.jpg') }}"
                                    alt="img" class="rounded-circle">
                            </span>
                            <div>
                                <div class="h5 mb-0">{{ $doctor->user?->name }}</div>
                                <div class="text-muted">{{ $doctor->department?->name }}</div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted">Specialization</div>
                            <div class="fw-semibold">
                                @if(is_array($doctor->specialization))
                                    {{ implode(', ', $doctor->specialization) }}
                                @else
                                    {{ $doctor->specialization ?? 'N/A' }}
                                @endif
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted">Consultation Fee</div>
                            <div class="fw-semibold">
                                @if (!is_null($doctor->consultation_fee))
                                    {{ number_format($doctor->consultation_fee, 2) }}
                                @else
                                    N/A
                                @endif
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted">Follow Up Fee</div>
                            <div class="fw-semibold">
                                @if (!is_null($doctor->follow_up_fee ?? null))
                                    {{ number_format($doctor->follow_up_fee, 2) }}
                                @else
                                    N/A
                                @endif
                            </div>
                        </div>
                        <div>
                            <div class="text-muted mb-1">Biography</div>
                            <div class="fs-13">
                                {{ $doctor->biography ?: 'No biography available.' }}
                            </div>
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
                                                            <input type="number" name="start_year" class="form-control"
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
