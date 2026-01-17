<x-app-layout>
    <div class="content">


        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card mt-2">
                    <div class="card-header">
                        <h4 class="card-title">Request Schedule Exception</h4>
                    </div>
                    <div class="card-body">
                        <div class="page-header">
                            <div class="row">
                                <div class="col-sm-12">
                                    <ul class="breadcrumb">
                                        <li class="breadcrumb-item"><a
                                                href="{{ route('doctor.schedule.exceptions.index') }}">Schedule
                                                Exceptions</a></li>
                                        <li class="breadcrumb-item active">New Request</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('doctor.schedule.exceptions.store') }}">
                            @csrf

                            <div class="form-group">
                                <label>Clinic <span class="text-danger">*</span></label>
                                <select class="select form-control" name="clinic_id" required>
                                    @foreach ($doctor->clinics as $clinic)
                                        <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Date <span class="text-danger">*</span></label>
                                <div class="cal-icon">
                                    <input type="text" class="form-control datetimepicker" name="exception_date"
                                        required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Type <span class="text-danger">*</span></label>
                                <select class="select form-control" name="type" id="type_select" required>
                                    <option value="day_off">Day Off (Unavailable)</option>
                                    <option value="custom_hours">Custom Hours (Time Change)</option>
                                </select>
                            </div>

                            <div class="row" id="time_inputs" style="display:none;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Start Time <span class="text-danger">*</span></label>
                                        <input type="time" class="form-control" name="start_time">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>End Time <span class="text-danger">*</span></label>
                                        <input type="time" class="form-control" name="end_time">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Reason</label>
                                <textarea class="form-control" name="reason" rows="3"></textarea>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Submit Request</button>
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

                $('#type_select').on('change', function() {
                    if ($(this).val() === 'custom_hours') {
                        $('#time_inputs').show();
                    } else {
                        $('#time_inputs').hide();
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
