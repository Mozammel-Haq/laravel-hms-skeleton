<x-app-layout>
<div class="container-fluid mx-2">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Request Schedule Exception</h5>
                    <a href="{{ route('doctor.schedule.exceptions.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i> Back
                    </a>
                </div>
                <hr>
                <div class="card-body">
                    <form action="{{ route('doctor.schedule.exceptions.store') }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                    id="start_date" name="start_date" value="{{ old('start_date') }}" required min="{{ date('Y-m-d') }}">
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                    id="end_date" name="end_date" value="{{ old('end_date') }}" required min="{{ date('Y-m-d') }}">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Exception Type</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_available" id="type_off" value="0"
                                        {{ old('is_available', '0') == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="type_off">
                                        Day Off (Unavailable)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_available" id="type_available" value="1"
                                        {{ old('is_available') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="type_available">
                                        Change Working Hours
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div id="time_fields" class="row mb-3" style="display: none;">
                            <div class="col-md-6">
                                <label for="start_time" class="form-label">Start Time</label>
                                <input type="time" class="form-control @error('start_time') is-invalid @enderror"
                                    id="start_time" name="start_time" value="{{ old('start_time') }}">
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="end_time" class="form-label">End Time</label>
                                <input type="time" class="form-control @error('end_time') is-invalid @enderror"
                                    id="end_time" name="end_time" value="{{ old('end_time') }}">
                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason</label>
                            <textarea class="form-control @error('reason') is-invalid @enderror"
                                id="reason" name="reason" rows="3" required>{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
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
    document.addEventListener('DOMContentLoaded', function() {
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');

        startDate.addEventListener('change', function() {
            if (!endDate.value) {
                endDate.value = this.value;
            }
            endDate.min = this.value;
        });

        const typeOff = document.getElementById('type_off');
        const typeAvailable = document.getElementById('type_available');
        const timeFields = document.getElementById('time_fields');
        const startTime = document.getElementById('start_time');
        const endTime = document.getElementById('end_time');

        function toggleTimeFields() {
            if (typeAvailable.checked) {
                timeFields.style.display = 'flex';
                startTime.required = true;
                endTime.required = true;
            } else {
                timeFields.style.display = 'none';
                startTime.required = false;
                endTime.required = false;
                startTime.value = '';
                endTime.value = '';
            }
        }

        typeOff.addEventListener('change', toggleTimeFields);
        typeAvailable.addEventListener('change', toggleTimeFields);

        // Initial check
        toggleTimeFields();
    });
</script>
@endpush
</x-app-layout>
