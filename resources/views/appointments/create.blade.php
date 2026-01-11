<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Book Appointment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('appointments.store') }}">
                        @csrf

                        <!-- Patient (Only for Admin/Doctor/Staff) -->
                        @if(auth()->user()->hasRole('Patient'))
                             <input type="hidden" name="patient_id" value="{{ auth()->user()->patient?->id }}">
                             <input type="hidden" name="booking_source" value="patient_portal">
                        @else
                            <div class="mb-4">
                                <x-input-label for="patient_id" :value="__('Patient')" />
                                <select id="patient_id" name="patient_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Select Patient</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                            {{ $patient->name }} ({{ $patient->patient_code }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('patient_id')" class="mt-2" />
                            </div>
                            <input type="hidden" name="booking_source" value="reception">
                        @endif

                        <!-- Doctor -->
                        <div class="mb-4">
                            <x-input-label for="doctor_id" :value="__('Doctor')" />
                            <select id="doctor_id" name="doctor_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Select Doctor</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->user->name }} - {{ $doctor->specialization }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('doctor_id')" class="mt-2" />
                        </div>

                        <!-- Date -->
                        <div class="mb-4">
                            <x-input-label for="appointment_date" :value="__('Date')" />
                            <x-text-input id="appointment_date" class="block mt-1 w-full" type="date" name="appointment_date" :value="old('appointment_date')" required />
                            <x-input-error :messages="$errors->get('appointment_date')" class="mt-2" />
                        </div>

                        <!-- Time -->
                        <div class="mb-4">
                            <x-input-label for="start_time" :value="__('Time')" />
                            <x-text-input id="start_time" class="block mt-1 w-full" type="time" name="start_time" :value="old('start_time')" required />
                            <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                        </div>

                        <!-- Type -->
                        <div class="mb-4">
                            <x-input-label for="appointment_type" :value="__('Type')" />
                            <select id="appointment_type" name="appointment_type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="in_person" {{ old('appointment_type') == 'in_person' ? 'selected' : '' }}>In Person</option>
                                <option value="online" {{ old('appointment_type') == 'online' ? 'selected' : '' }}>Online</option>
                            </select>
                            <x-input-error :messages="$errors->get('appointment_type')" class="mt-2" />
                        </div>

                        <!-- Reason -->
                        <div class="mb-4">
                            <x-input-label for="reason_for_visit" :value="__('Reason for Visit')" />
                            <textarea id="reason_for_visit" name="reason_for_visit" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('reason_for_visit') }}</textarea>
                            <x-input-error :messages="$errors->get('reason_for_visit')" class="mt-2" />
                        </div>

                        <!-- Fee Display -->
                        <div class="mb-4" id="fee-container" style="display: none;">
                            <x-input-label value="Estimated Consultation Fee" />
                            <div class="mt-1 p-3 bg-gray-50 border border-gray-200 rounded-md">
                                <span class="text-xl font-bold text-gray-800" id="fee-display">-</span>
                                <span class="text-sm text-gray-500 ml-2" id="visit-type-display"></span>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Book Appointment') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const doctorSelect = document.getElementById('doctor_id');
            const patientSelect = document.getElementById('patient_id');
            const feeContainer = document.getElementById('fee-container');
            const feeDisplay = document.getElementById('fee-display');
            const visitTypeDisplay = document.getElementById('visit-type-display');
            
            // Handle case where patient_id is a hidden input (Patient Portal)
            const patientHiddenInput = document.querySelector('input[name="patient_id"][type="hidden"]');
            
            function updateFee() {
                const doctorId = doctorSelect ? doctorSelect.value : null;
                let patientId = null;
                
                if (patientSelect) {
                    patientId = patientSelect.value;
                } else if (patientHiddenInput) {
                    patientId = patientHiddenInput.value;
                }
                
                if (doctorId && patientId) {
                    // Use the correct route structure
                    fetch(`/api/doctors/${doctorId}/fee?patient_id=${patientId}`)
                        .then(response => {
                            if (!response.ok) throw new Error('Network response was not ok');
                            return response.json();
                        })
                        .then(data => {
                            feeContainer.style.display = 'block';
                            // Format currency (assuming system uses default currency symbol, e.g. $)
                            const formattedFee = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(data.fee);
                            feeDisplay.textContent = formattedFee;
                            
                            const typeText = data.visit_type === 'follow_up' 
                                ? '(Follow-up Visit)' 
                                : '(New Consultation)';
                            visitTypeDisplay.textContent = typeText;
                            
                            // Visual cue for follow-up discount
                            if (data.visit_type === 'follow_up') {
                                visitTypeDisplay.classList.add('text-green-600');
                                visitTypeDisplay.classList.remove('text-gray-500');
                            } else {
                                visitTypeDisplay.classList.add('text-gray-500');
                                visitTypeDisplay.classList.remove('text-green-600');
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching fee:', error);
                            feeContainer.style.display = 'none';
                        });
                } else {
                    feeContainer.style.display = 'none';
                }
            }
            
            if (doctorSelect) {
                doctorSelect.addEventListener('change', updateFee);
            }
            
            if (patientSelect) {
                patientSelect.addEventListener('change', updateFee);
            }
            
            // Run on load in case values are pre-filled (e.g. back button or validation error)
            updateFee();
        });
    </script>
</x-app-layout>
