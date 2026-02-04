<x-app-layout>
    <div class="container-fluid py-1 mx-2">
        <!-- Header -->
        <div class="row align-items-center px-3 py-3 border-bottom bg-primary-subtle text-primary">
            <div class="col">
                <h4 class="mb-1 fw-bold text-dark">Book Appointment</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('appointments.booking.index') }}" class="text-decoration-none">Find a Doctor</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dr. {{ $doctor->user?->name ?? 'Doctor' }}</li>
                    </ol>
                </nav>
            </div>
            <div class="col-auto">
                <a href="{{ route('appointments.booking.index') }}" class="btn btn-outline-primary">
                    <i class="ti ti-arrow-left me-1"></i> Back to Search
                </a>
            </div>
        </div>
        <div class="row g-4">
            <!-- Doctor Profile Side -->
            <div class="col-lg-4 p-0">
                <div class="card border border-secondary-subtle shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <!-- Avatar -->
                        <div class="position-relative d-inline-block mb-3">
                            <div class="rounded-circle border border-3 border-white shadow-sm overflow-hidden" style="width: 120px; height: 120px;">
                                <img src="{{ $doctor->profile_photo ? asset($doctor->profile_photo) : asset('assets/img/doctors/doctor-01.jpg') }}"
                                    class="w-100 h-100 object-fit-cover"
                                    alt="Doctor Image">
                            </div>
                        </div>

                        <!-- Basic Info -->
                        <h5 class="fw-bold text-dark mb-1">Dr. {{ $doctor->user?->name ?? 'Deleted Doctor' }}</h5>
                        <p class="text-primary fw-medium mb-3">{{ $doctor->department->name ?? 'General' }}</p>

                        <!-- Clinics -->
                        <div class="mb-4 d-flex flex-wrap justify-content-center gap-2">
                            @foreach ($doctor->clinics as $clinic)
                                <span class="badge bg-light text-dark border border-secondary-subtle d-flex align-items-center gap-1 px-3 py-2">
                                    @if($clinic->logo_path)
                                        <img src="{{ asset("/") }}/{{ Storage::url($clinic->logo_path) }}" class="rounded-circle" style="width: 16px; height: 16px; object-fit: cover;">
                                    @else
                                        <i class="ti ti-building-hospital text-muted"></i>
                                    @endif
                                    {{ $clinic->name }}
                                </span>
                            @endforeach
                        </div>

                        <hr class="border-secondary-subtle my-4">

                        <!-- Details -->
                        <div class="text-start">
                            <div class="mb-3">
                                <label class="text-muted small text-uppercase fw-bold mb-1">Specialization</label>
                                <div class="d-flex flex-wrap gap-1">
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
                                        $pieces = array_slice($pieces, 0, 5);
                                    @endphp
                                    @forelse($pieces as $spec)
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ $spec }}</span>
                                    @empty
                                        <span class="text-muted small">Not set</span>
                                    @endforelse
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3 text-secondary"><i class="ti ti-briefcase fs-5"></i></div>
                                        <div>
                                            <small class="text-muted d-block">Experience</small>
                                            <span class="fw-medium text-dark">{{ $doctor->experience_years ? $doctor->experience_years . ' Years' : 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3 text-secondary"><i class="ti ti-gender-male-female fs-5"></i></div>
                                        <div>
                                            <small class="text-muted d-block">Gender</small>
                                            <span class="fw-medium text-dark text-capitalize">{{ $doctor->gender ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-light rounded p-3 border border-secondary-subtle mb-4">
                                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom border-secondary-subtle">
                                    <span class="text-muted">Consultation Fee</span>
                                    <span class="fw-bold text-primary">
                                        {{ !is_null($doctor->consultation_fee) ? number_format($doctor->consultation_fee, 2) . ' BDT' : 'N/A' }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Follow-up Fee</span>
                                    <span class="fw-bold text-primary">
                                        {{ !is_null($doctor->follow_up_fee) ? number_format($doctor->follow_up_fee, 2) . ' BDT' : 'N/A' }}
                                    </span>
                                </div>
                            </div>

                            <div>
                                <label class="text-muted small text-uppercase fw-bold mb-1">About Doctor</label>
                                <p class="text-muted small mb-0">
                                    {{ $doctor->biography ?: 'No biography available.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Form Side -->
            <div class="col-lg-8">
                <div class="card border border-secondary-subtle shadow-sm h-100">
                    <div class="card-header bg-transparent border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="ti ti-calendar-plus me-2 text-primary"></i> Book Appointment
                        </h5>
                    </div>

                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('appointments.booking.store') }}" id="bookingForm">
                            @csrf

                            <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
                            <input type="hidden" name="start_time" id="start_time">
                            <input type="hidden" name="end_time" id="end_time">

                            <!-- Patient & Clinic Selection -->
                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark">Patient <span class="text-danger">*</span></label>
                                    <select class="form-select select2-patient" name="patient_id" id="patient_id" required>
                                        <option value="">Select Patient</option>
                                        @if (isset($patients))
                                            @foreach ($patients as $patient)
                                                <option value="{{ $patient->id }}" selected>
                                                    {{ $patient->name }} ({{ $patient->patient_code }})
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark">Clinic Location <span class="text-danger">*</span></label>
                                    <select class="form-select" name="clinic_id" id="clinic_id" required>
                                        @foreach ($doctor->clinics as $clinic)
                                            <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Date & Fee -->
                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark">Appointment Date <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="ti ti-calendar"></i></span>
                                        <input type="text" class="form-control datetimepicker border-start-0 ps-0"
                                            name="appointment_date" id="appointment_date" required placeholder="YYYY-MM-DD">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark">Calculated Fee</label>
                                    <input type="text" class="form-control bg-light" id="fee_display"
                                        value="Select Patient first" readonly>
                                    <small id="fee_note" class="text-muted mt-1 d-block"></small>
                                </div>
                            </div>

                            <!-- Available Slots -->
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark mb-2">Available Slots</label>
                                <div id="slots_container" class="border border-secondary-subtle rounded p-4 bg-light text-center">
                                    <div class="text-muted">
                                        <i class="ti ti-calendar-event fs-2 mb-2 d-block opacity-50"></i>
                                        Please select a date to view available slots.
                                    </div>
                                </div>
                                <div id="slot_error" class="text-danger mt-2" style="display:none;"></div>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-end border-top pt-4 mt-4">
                                <button type="submit" class="btn btn-primary px-5" id="submitBtn" disabled>
                                    <i class="ti ti-check me-1"></i> Confirm Booking
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Initialize Select2 with AJAX
                $('.select2-patient').select2({
                    ajax: {
                        url: '{{ route('patients.search') }}',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                term: params.term,
                                page: params.page
                            };
                        },
                        processResults: function(data, params) {
                            params.page = params.page || 1;
                            return {
                                results: data.results,
                                pagination: {
                                    more: data.pagination.more
                                }
                            };
                        },
                        cache: true
                    },
                    placeholder: 'Search for a patient',
                    minimumInputLength: 0,
                    allowClear: true,
                    width: '100%'
                });
            });

            // Keep your original JS intact
            $(document).ready(function() {
                if ($('.datetimepicker').length > 0) {
                    $('.datetimepicker').datetimepicker({
                        format: 'YYYY-MM-DD',
                        minDate: new Date(),
                        icons: {
                            up: "fas fa-angle-up",
                            down: "fas fa-angle-down",
                            next: 'fas fa-angle-right',
                            previous: 'fas fa-angle-left'
                        }
                    });
                }

                $('#patient_id').on('change', function() {
                    var patientId = $(this).val();
                    if (!patientId) {
                        $('#fee_display').val('Select Patient first');
                        $('#fee_note').text('');
                        return;
                    }

                    $.ajax({
                        url: '{{ route('appointments.booking.fee', $doctor->id) }}',
                        type: 'GET',
                        data: {
                            patient_id: patientId
                        },
                        success: function(response) {
                            $('#fee_display').val(response.fee + ' BDT');
                            if (response.is_discounted) {
                                $('#fee_note').html('<span class="text-success"><i class="ti ti-tag me-1"></i> Returning patient discount applied (' +
                                    response.type + ').</span>');
                            } else {
                                $('#fee_note').text('Standard consultation fee (' + response.type +
                                    ').');
                            }
                        },
                        error: function() {
                            $('#fee_display').val('Error calculating fee');
                        }
                    });
                });

                $('.datetimepicker').on('dp.change', function(e) {
                    if (!e.date) {
                        $('#slots_container').html(
                            '<div class="text-muted p-4 text-center">Please select a date to view available slots.</div>');
                        $('#submitBtn').prop('disabled', true);
                        $('#start_time').val('');
                        $('#end_time').val('');
                        return;
                    }

                    var selected = e.date.startOf('day');
                    var today = moment().startOf('day');

                    if (selected.isBefore(today)) {
                        $('#slots_container').html(
                            '<div class="text-danger p-4 text-center"><i class="ti ti-alert-circle me-2"></i> You cannot book appointments for past dates.</div>');
                        $('#submitBtn').prop('disabled', true);
                        $('#start_time').val('');
                        $('#end_time').val('');
                        return;
                    }

                    var date = selected.format('YYYY-MM-DD');
                    loadSlots(date);
                });

                function loadSlots(date) {
                    $('#slots_container').html(
                        '<div class="text-center p-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>'
                    );
                    $('#submitBtn').prop('disabled', true);
                    $('#start_time').val('');
                    $('#end_time').val('');

                    $.ajax({
                        url: '{{ route('appointments.booking.slots', $doctor->id) }}',
                        type: 'GET',
                        data: {
                            date: date
                        },
                        success: function(response) {
                            var slots = response.slots;
                            var html = '<div class="row g-3">';
                            if (slots.length === 0) {
                                html = '<div class="text-center p-4"><p class="text-danger mb-0"><i class="ti ti-calendar-off me-2"></i> No slots available for this date.</p></div>';
                            } else {
                                $.each(slots, function(index, slot) {
                                    var btnClass = slot.is_booked ? 'btn-secondary disabled' :
                                        'btn-outline-primary slot-btn';
                                    var disabled = slot.is_booked ? 'disabled' : '';

                                    html += '<div class="col-6 col-sm-4 col-md-3">';
                                    html += '<button type="button" class="btn ' + btnClass +
                                        ' w-100 py-2" ' + disabled +
                                        ' data-start="' + slot.start_time + '" data-end="' + slot
                                        .end_time + '">';
                                    html += slot.start_time + ' - ' + slot.end_time;
                                    html += '</button></div>';
                                });
                                html += '</div>';
                            }
                            $('#slots_container').html(html);
                        },
                        error: function() {
                            $('#slots_container').html('<div class="text-center p-4"><p class="text-danger mb-0">Error fetching slots.</p></div>');
                        }
                    });
                }

                $(document).on('click', '.slot-btn', function() {
                    $('.slot-btn').removeClass('btn-primary').addClass('btn-outline-primary');
                    $(this).removeClass('btn-outline-primary').addClass('btn-primary');

                    $('#start_time').val($(this).data('start'));
                    $('#end_time').val($(this).data('end'));
                    $('#submitBtn').prop('disabled', false);
                });
            });
        </script>

        <style>
            .slot-btn {
                transition: all 0.2s ease;
                font-size: 0.9rem;
            }

            .slot-btn:hover:not(.disabled) {
                transform: translateY(-2px);
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            }

            .slot-btn.disabled {
                opacity: 0.6;
                cursor: not-allowed;
            }
        </style>
    @endpush
</x-app-layout>
