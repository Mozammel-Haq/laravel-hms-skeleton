<x-app-layout>
    <h5 class="mb-3">Visit #{{ $visit->id }}</h5>
    <div class="row g-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="mb-2">Appointment: #{{ $visit->appointment_id }}</div>
                    <div class="mb-2">Patient: {{ optional($visit->appointment->patient)->name }}</div>
                    <div class="mb-2">Status: {{ $visit->visit_status }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="mb-2">Consultation: {{ optional($visit->consultation)->id }}</div>
                    <div class="mb-2">Diagnosis: {{ optional($visit->consultation)->diagnosis }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
