<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Appointment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('appointments.update', $appointment) }}">
                        @csrf
                        @method('PUT')

                        <!-- Patient Info (Read Only) -->
                        <div class="mb-4">
                            <x-input-label :value="__('Patient')" />
                            <div class="p-2 bg-gray-100 rounded mt-1">
                                {{ $appointment->patient->name }} ({{ $appointment->patient->patient_code }})
                            </div>
                        </div>

                        <!-- Doctor Info (Read Only for now, complexity to change doctor) -->
                        <div class="mb-4">
                            <x-input-label :value="__('Doctor')" />
                            <div class="p-2 bg-gray-100 rounded mt-1">
                                {{ $appointment->doctor->user->name }} - {{ $appointment->doctor->specialization }}
                            </div>
                        </div>

                        <!-- Date -->
                        <div class="mb-4">
                            <x-input-label for="appointment_date" :value="__('Date')" />
                            <x-text-input id="appointment_date" class="block mt-1 w-full" type="date"
                                name="appointment_date" :value="old('appointment_date', $appointment->appointment_date)" required />
                            <x-input-error :messages="$errors->get('appointment_date')" class="mt-2" />
                        </div>

                        <!-- Time -->
                        <div class="mb-4">
                            <x-input-label for="start_time" :value="__('Time')" />
                            <x-text-input id="start_time" class="block mt-1 w-full" type="time" name="start_time"
                                :value="old(
                                    'start_time',
                                    \Carbon\Carbon::parse($appointment->start_time)->format('H:i'),
                                )" required />
                            <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                        </div>

                        <!-- Type -->
                        <div class="mb-4">
                            <x-input-label for="appointment_type" :value="__('Type')" />
                            <select id="appointment_type" name="appointment_type"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="in_person"
                                    {{ old('appointment_type', $appointment->appointment_type) == 'in_person' ? 'selected' : '' }}>
                                    In Person</option>
                                <option value="online"
                                    {{ old('appointment_type', $appointment->appointment_type) == 'online' ? 'selected' : '' }}>
                                    Online</option>
                            </select>
                            <x-input-error :messages="$errors->get('appointment_type')" class="mt-2" />
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="pending"
                                    {{ old('status', $appointment->status) == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="confirmed"
                                    {{ old('status', $appointment->status) == 'confirmed' ? 'selected' : '' }}>Confirmed
                                </option>
                                <option value="cancelled"
                                    {{ old('status', $appointment->status) == 'cancelled' ? 'selected' : '' }}>
                                    Cancelled</option>
                                <option value="completed"
                                    {{ old('status', $appointment->status) == 'completed' ? 'selected' : '' }}>
                                    Completed</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <!-- Reason -->
                        <div class="mb-4">
                            <x-input-label for="reason_for_visit" :value="__('Reason for Visit')" />
                            <textarea id="reason_for_visit" name="reason_for_visit"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                rows="3">{{ old('reason_for_visit', $appointment->reason_for_visit) }}</textarea>
                            <x-input-error :messages="$errors->get('reason_for_visit')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('appointments.index') }}"
                                class="text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
                            <x-primary-button>
                                {{ __('Update Appointment') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
