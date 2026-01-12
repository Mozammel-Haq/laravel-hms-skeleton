<x-app-layout>
    <div class="content">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
<a href="{{ route('appointments.booking.index') }}">Find a Doctor</a>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Doctor Profile Side -->
            <div class="col-md-4">
                <div class="card profile-widget">
                    <div class="doctor-img text-center">
                        <a href="#" class="avatar-xxl">
                            <img class="avatar-img"
                                src="{{ $doctor->user->profile_photo_url ?? asset('assets/img/profiles/avatar-01.jpg') }}"
                                alt="User Image">
                        </a>
                    </div>
                    <h4 class="doctor-name text-center">Dr. {{ $doctor->user->name }}</h4>
                    <div class="doc-prof text-center">{{ $doctor->department->name ?? 'General' }}</div>
                    <div class="user-country text-center">
                        <i class="fa fa-map-marker"></i>
                        @foreach ($doctor->clinics as $clinic)
                            {{ $clinic->name }}@if (!$loop->last)
                                ,
                            @endif
                        @endforeach
                    </div>
                    <hr>
                    <div class="doc-info-cont">
                        <h5 class="mb-2">Consultation Fee</h5>
                        <p>{{ number_format($doctor->consultation_fee, 2) }} BDT</p>
                    </div>
                </div>
            </div>

            <!-- Booking Form Side -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Book Appointment</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('appointments.booking.store') }}" id="bookingForm">
                            @csrf
                            <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
                            <input type="hidden" name="start_time" id="start_time">
                            <input type="hidden" name="end_time" id="end_time">

                            <div class="row">
                                <!-- Patient Selection -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Patient <span class="text-danger">*</span></label>
                                        <select class="select form-control" name="patient_id" id="patient_id" required>
                                            <option value="">Select Patient</option>
                                            @foreach ($patients as $patient)
                                                <option value="{{ $patient->id }}">{{ $patient->name }}
                                                    ({{ $patient->patient_code ?? $patient->id }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Clinic Selection -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Clinic Location <span class="text-danger">*</span></label>
                                        <select class="select form-control" name="clinic_id" id="clinic_id" required>
                                            @foreach ($doctor->clinics as $clinic)
                                                <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Date Selection -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Appointment Date <span class="text-danger">*</span></label>
                                        <div class="cal-icon">
                                            <input type="text" class="form-control datetimepicker"
                                                name="appointment_date" id="appointment_date" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Fee Display -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Calculated Fee</label>
                                        <input type="text" class="form-control" id="fee_display"
                                            value="Select Patient first">
                                        <small id="fee_note" class="text-muted"></small>
                                    </div>
                                </div>
                            </div>

                            <!-- Slots Section -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5>Available Slots</h5>
                                    <div id="slots_container" class="mt-3">
                                        <p class="text-muted">Please select a date to view available slots.</p>
                                    </div>
                                    <div id="slot_error" class="text-danger mt-2" style="display:none;"></div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12 text-end">
                                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>Confirm
                                        Booking</button>
                                </div>
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
                // Initialize Datepicker
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

                // Fetch Fee on Patient Change
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
                            console.log(response)
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

                // Fetch Slots on Date Change
                $('.datetimepicker').on('dp.change', function(e) {
                    var date = e.date.format('YYYY-MM-DD');
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
                                    var title = slot.is_booked ? 'Booked' : 'Available';

                                    html += '<div class="col-6 col-sm-4 col-md-3 mb-3">';
                                    html += '<button type="button" class="btn ' + btnClass +
                                        ' w-100" ' + disabled + ' ';
                                    html += 'data-start="' + slot.start_time + '" data-end="' + slot
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

                // Handle Slot Selection
                $(document).on('click', '.slot-btn', function() {
                    $('.slot-btn').removeClass('btn-primary').addClass('btn-outline-primary');
                    $(this).removeClass('btn-outline-primary').addClass('btn-primary');

                    var start = $(this).data('start');
                    var end = $(this).data('end');

                    $('#start_time').val(start);
                    $('#end_time').val(end);
                    $('#submitBtn').prop('disabled', false);
                });
            });
        </script>
        <style>
            .slot-btn {
                transition: all 0.3s;
            }

            .slot-btn.disabled {
                opacity: 0.6;
                cursor: not-allowed;
            }
        </style>
    @endpush
</x-app-layout>
