<x-app-layout>
    <div class="container-fluid">

        <!-- Breadcrumb -->
        <div class="mb-2 mt-2">
            <a href="{{ route('appointments.booking.index') }}" class="btn btn-outline-secondary">
                ← Find a Doctor
            </a>
        </div>

        <div class="row g-4">
            <!-- Doctor Profile Side -->
            <div class="col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-3">

                        <!-- Avatar -->
                        <div class="text-center mb-3">
                            <img src="{{ $doctor->profile_photo ? asset($doctor->profile_photo) : asset('assets/img/doctors/doctor-01.jpg') }}"
                                class="rounded-circle border" style="width:110px;height:110px;object-fit:cover;"
                                alt="Doctor Image">
                        </div>

                        <!-- Basic Info -->
                        <h5 class="fw-semibold text-center mb-1">Dr. {{ $doctor->user?->name ?? 'Deleted Doctor' }}</h5>
                        <div class="text-muted text-center mb-2">{{ $doctor->department->name ?? 'General' }}</div>

                        <!-- Clinics -->
                        <div class="mb-3 text-center">
                            @foreach ($doctor->clinics as $clinic)
                                <span class="badge bg-light text-muted me-1 mb-1 d-inline-flex align-items-center gap-1">
                                    @if($clinic->logo_path)
                                        <img src="{{ Storage::url($clinic->logo_path) }}" class="rounded-circle" style="width: 16px; height: 16px; object-fit: cover;">
                                    @else
                                        <i class="fa fa-map-marker"></i>
                                    @endif
                                    {{ $clinic->name }}
                                </span>
                            @endforeach
                        </div>

                        <hr class="my-2">

                        <!-- Details -->
                        <div class="small">
                            <div class="mb-2 d-flex align-items-center">
                                <i class="fa fa-stethoscope me-2 text-muted"></i>
                                <span>{{ $doctor->specialization ?? 'Specialization not set' }}</span>
                            </div>

                            <div class="mb-2 d-flex align-items-center">
                                <i class="fa fa-briefcase me-2 text-muted"></i>
                                <span>
                                    {{ $doctor->experience_years ? $doctor->experience_years . ' years experience' : 'Experience not specified' }}
                                </span>
                            </div>

                            <div class="mb-2 d-flex align-items-center">
                                <i class="fa fa-user me-2 text-muted"></i>
                                <span>{{ $doctor->gender ?? 'Gender not specified' }}</span>
                            </div>

                            <div class="mb-2 d-flex align-items-center">
                                <i class="fa fa-tint me-2 text-muted"></i>
                                <span>{{ $doctor->blood_group ?? 'Blood group not specified' }}</span>
                            </div>

                            <div class="mb-2 d-flex align-items-center">
                                <i class="fa fa-phone me-2 text-muted"></i>
                                <span>{{ $doctor->user?->phone ?? 'Phone not set' }}</span>
                            </div>

                            <div class="mb-3 d-flex align-items-center">
                                <i class="fa fa-envelope me-2 text-muted"></i>
                                <span>{{ $doctor->user?->email ?? 'Email not set' }}</span>
                            </div>

                            <hr class="my-2">

                            <div class="mb-1 d-flex justify-content-between">
                                <span class="text-muted">Consultation Fee</span>
                                <strong>
                                    {{ !is_null($doctor->consultation_fee) ? number_format($doctor->consultation_fee, 2) . ' BDT' : 'N/A' }}
                                </strong>
                            </div>
                            <div class="mb-3 d-flex justify-content-between">
                                <span class="text-muted">Follow-up Fee</span>
                                <strong>
                                    {{ !is_null($doctor->follow_up_fee ?? null) ? number_format($doctor->follow_up_fee, 2) . ' BDT' : 'N/A' }}
                                </strong>
                            </div>

                            <div>
                                <small class="text-muted d-block mb-1">About Doctor</small>
                                <p class="mb-0 text-muted">
                                    {{ $doctor->biography ?: 'No biography available.' }}
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Booking Form Side -->
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 fw-semibold">Book Appointment</h5>
                    </div>

                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('appointments.booking.store') }}" id="bookingForm">
                            @csrf

                            <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
                            <input type="hidden" name="start_time" id="start_time">
                            <input type="hidden" name="end_time" id="end_time">

                            <!-- Patient & Clinic Selection -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Patient <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm select2-patient" name="patient_id"
                                        id="patient_id" required>
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
                                    <label class="form-label fw-medium">Clinic Location <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm" name="clinic_id" id="clinic_id" required>
                                        @foreach ($doctor->clinics as $clinic)
                                            <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Date & Fee -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Appointment Date <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm datetimepicker"
                                        name="appointment_date" id="appointment_date" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Calculated Fee</label>
                                    <input type="text" class="form-control form-control-sm" id="fee_display"
                                        value="Select Patient first" readonly>
                                    <small id="fee_note" class="text-muted"></small>
                                </div>
                            </div>

                            <!-- Available Slots -->
                            <div class="mb-4">
                                <h6 class="fw-semibold mb-2">Available Slots</h6>
                                <div id="slots_container" class="border rounded p-3 bg-light">
                                    <p class="text-muted mb-0">Please select a date to view available slots.</p>
                                </div>
                                <div id="slot_error" class="text-danger mt-2" style="display:none;"></div>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary px-4" id="submitBtn" disabled>
                                    Confirm Booking
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
                                $('#fee_note').text('Returning patient discount applied (' +
                                    response.type + ').');
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
                            '<p class="text-muted mb-0">Please select a date to view available slots.</p>');
                        $('#submitBtn').prop('disabled', true);
                        $('#start_time').val('');
                        $('#end_time').val('');
                        return;
                    }

                    var selected = e.date.startOf('day');
                    var today = moment().startOf('day');

                    if (selected.isBefore(today)) {
                        $('#slots_container').html(
                            '<p class="text-danger mb-0">You cannot book appointments for past dates.</p>');
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
                        '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
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
                            var html = '<div class="row">';
                            if (slots.length === 0) {
                                html = '<p class="text-danger">No slots available for this date.</p>';
                            } else {
                                $.each(slots, function(index, slot) {
                                    var btnClass = slot.is_booked ? 'btn-secondary disabled' :
                                        'btn-outline-primary slot-btn';
                                    var disabled = slot.is_booked ? 'disabled' : '';
                                    html += '<div class="col-6 col-sm-4 col-md-3 mb-3">';
                                    html += '<button type="button" class="btn ' + btnClass +
                                        ' w-100" ' + disabled +
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
                            $('#slots_container').html('<p class="text-danger">Error fetching slots.</p>');
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
            /* UI Enhancements Only */
            .slot-btn {
                transition: all 0.2s ease;
                font-size: 0.85rem;
            }

            .slot-btn:hover:not(.disabled) {
                transform: translateY(-1px);
            }

            .slot-btn.disabled {
                opacity: 0.6;
                cursor: not-allowed;
            }
        </style>
    @endpush
</x-app-layout>
