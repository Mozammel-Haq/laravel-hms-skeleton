<x-app-layout>
    <h5 class="mb-3">Bed Assignment #{{ $assignment->id }}</h5>
    <div class="card">
        <div class="card-body">
            <div class="mb-2">Bed: {{ optional($assignment->bed)->bed_number }}</div>
            <div class="mb-2">Patient: {{ optional($assignment->admission->patient)->name }}</div>
            <div class="mb-2">Assigned At: {{ $assignment->assigned_at }}</div>
            <div class="mb-2">Released At: {{ $assignment->released_at }}</div>
        </div>
    </div>
</x-app-layout>
