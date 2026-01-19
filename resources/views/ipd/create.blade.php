<x-app-layout>
    <div class="container-fluid">


        <div class="card border-0 mt-2 px-3 py-2">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">Admit Patient</h4>
                        <p class="text-muted mb-0">Create a new inpatient admission</p>
                    </div>
                    <a href="{{ route('ipd.index') }}" class="btn btn-outline-primary">
                        <i class="ti ti-arrow-left me-1"></i> Back to IPD
                    </a>
                </div>
                <hr>
                <form action="{{ route('ipd.store') }}" method="POST" class="p-3 border rounded">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <x-input-label for="patient_id" :value="__('Select Patient')" />
                            <select id="patient_id" name="patient_id"
                                class="form-select mt-1 block w-full select2-patient" required>
                                <option value="">Select a patient...</option>
                            </select>
                            <x-input-error :messages="$errors->get('patient_id')" class="mt-2" />
                        </div>

                        <div class="col-md-6">
                            <x-input-label for="doctor_id" :value="__('Attending Doctor')" />
                            <select id="doctor_id" name="doctor_id" class="form-select mt-1 block w-full" required>
                                <option value="">Select a doctor...</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}"
                                        {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        Dr. {{ $doctor->user?->name ?? 'Unknown' }}
                                        ({{ $doctor->department?->name ?? 'General' }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('doctor_id')" class="mt-2" />
                        </div>

                        <div class="col-md-6">
                            <x-input-label for="admission_date" :value="__('Admission Date')" />
                            <x-text-input id="admission_date" class="block mt-1 w-full form-control"
                                type="datetime-local" name="admission_date" :value="old('admission_date', now()->format('Y-m-d\TH:i'))" required />
                            <x-input-error :messages="$errors->get('admission_date')" class="mt-2" />
                        </div>

                        <div class="col-md-12">
                            <x-input-label for="admission_reason" :value="__('Admission Notes / Reason')" />
                            <textarea id="admission_reason" name="admission_reason" class="form-control mt-1 block w-full" rows="4"
                                placeholder="Enter reason for admission and initial notes...">{{ old('admission_reason') }}</textarea>
                            <x-input-error :messages="$errors->get('admission_reason')" class="mt-2" />
                        </div>
                    </div>

                    <div class="row g-4 mt-3">
                        <div class="col-md-8">
                            <div class="mt-2 d-flex justify-content-end">
                                <input type="hidden" name="bed_id" id="bed_id" value="{{ old('bed_id') }}">
                                <button type="submit" class="btn btn-primary" id="admit-button">
                                    <i class="ti ti-check me-1"></i> Admit Patient
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('bed_id')" class="mt-2" />
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm mt-2"
                                x-data='bedMatrix({
                                wards: @json($wards),
                                initialBedId: {{ old('bed_id') ? (int) old('bed_id') : (request('bed_id') ? (int) request('bed_id') : 'null') }}
                            })'>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <div class="fw-semibold">Bed Selection</div>
                                            <div class="text-muted small">Choose an available bed</div>
                                        </div>
                                        <a href="{{ route('ipd.bed_status') }}"
                                            class="btn btn-sm btn-outline-secondary">
                                            <i class="ti ti-grid-3x3"></i>
                                        </a>
                                    </div>
                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <label class="form-label small mb-1">Ward</label>
                                            <select class="form-select form-select-sm" x-model.number="selectedWardId"
                                                @change="onWardChange">
                                                <template x-for="ward in wards" :key="ward.id">
                                                    <option :value="ward.id" x-text="ward.name"></option>
                                                </template>
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small mb-1">Room</label>
                                            <select class="form-select form-select-sm" x-model.number="selectedRoomId">
                                                <template x-for="room in rooms" :key="room.id">
                                                    <option :value="room.id"
                                                        x-text="room.room_number + ' (' + (room.room_type || '') + ')'">
                                                    </option>
                                                </template>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 mb-2">
                                        <div class="d-flex align-items-center gap-1">
                                            <span class="badge bg-success-subtle text-success">
                                                <i class="ti ti-bed"></i>
                                            </span>
                                            <span class="small text-muted">Available</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-1">
                                            <span class="badge bg-danger-subtle text-danger">
                                                <i class="ti ti-bed"></i>
                                            </span>
                                            <span class="small text-muted">Occupied</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-1">
                                            <span class="badge bg-secondary-subtle text-secondary">
                                                <i class="ti ti-tools"></i>
                                            </span>
                                            <span class="small text-muted">Maintenance</span>
                                        </div>
                                    </div>
                                    <div class="border rounded p-2" style="max-height: 260px; overflow-y: auto;">
                                        <div class="d-flex flex-wrap gap-2">
                                            <template x-for="bed in beds" :key="bed.id">
                                                <button type="button" class="btn btn-sm position-relative"
                                                    :class="bedButtonClass(bed)" @click="selectBed(bed)">
                                                    <i class="ti ti-bed me-1"></i>
                                                    <span x-text="bed.bed_number"></span>
                                                    <span
                                                        class="position-absolute top-0 end-0 translate-middle badge rounded-pill bg-primary"
                                                        x-show="isSelected(bed)">
                                                        <i class="ti ti-check"></i>
                                                    </span>
                                                </button>
                                            </template>
                                            <div x-show="beds.length === 0" class="text-muted small">
                                                No beds found for this room.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
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

            function bedMatrix(config) {
                return {
                    wards: config.wards || [],
                    selectedWardId: null,
                    selectedRoomId: null,
                    selectedBedId: config.initialBedId,
                    init() {
                        if (this.wards.length) {
                            const wardWithRooms = this.wards.find(w => w.rooms && w.rooms.length);
                            if (wardWithRooms) {
                                this.selectedWardId = wardWithRooms.id;
                                this.selectedRoomId = wardWithRooms.rooms[0].id;
                            } else {
                                this.selectedWardId = this.wards[0].id;
                                this.selectedRoomId = null;
                            }
                        }
                        if (this.selectedBedId) {
                            for (const ward of this.wards) {
                                for (const room of ward.rooms || []) {
                                    for (const bed of room.beds || []) {
                                        if (bed.id === this.selectedBedId) {
                                            this.selectedWardId = ward.id;
                                            this.selectedRoomId = room.id;
                                        }
                                    }
                                }
                            }
                        }
                        this.updateHidden();
                        this.updateButtonState();
                    },
                    get rooms() {
                        const wardId = Number(this.selectedWardId);
                        const ward = this.wards.find(w => Number(w.id) === wardId);
                        return ward && ward.rooms ? ward.rooms : [];
                    },
                    get beds() {
                        const wardId = Number(this.selectedWardId);
                        const ward = this.wards.find(w => Number(w.id) === wardId);
                        if (!ward || !ward.rooms) return [];
                        const roomId = Number(this.selectedRoomId);
                        const room = ward.rooms.find(r => Number(r.id) === roomId);
                        return room && room.beds ? room.beds : [];
                    },
                    onWardChange() {
                        const wardId = Number(this.selectedWardId);
                        const ward = this.wards.find(w => Number(w.id) === wardId);
                        if (ward && ward.rooms && ward.rooms.length) {
                            this.selectedRoomId = ward.rooms[0].id;
                        } else {
                            this.selectedRoomId = null;
                        }
                    },
                    selectBed(bed) {
                        if (bed.status !== 'available') return;
                        this.selectedBedId = bed.id;
                        this.updateHidden();
                        this.updateButtonState();
                    },
                    isSelected(bed) {
                        return this.selectedBedId === bed.id;
                    },
                    bedButtonClass(bed) {
                        let classes = ['btn-outline-secondary'];
                        if (bed.status === 'available') {
                            classes = ['btn-outline-success'];
                        } else if (bed.status === 'occupied') {
                            classes = ['btn-outline-danger'];
                        } else if (bed.status === 'maintenance') {
                            classes = ['btn-outline-secondary', 'opacity-50'];
                        }
                        if (this.isSelected(bed)) {
                            classes.push('active');
                        }
                        return classes.join(' ');
                    },
                    updateHidden() {
                        const input = document.getElementById('bed_id');
                        if (input) {
                            input.value = this.selectedBedId || '';
                        }
                    },
                    updateButtonState() {
                        const button = document.getElementById('admit-button');
                        if (button) {
                            if (this.selectedBedId) {
                                button.removeAttribute('disabled');
                            } else {
                                button.setAttribute('disabled', 'disabled');
                            }
                        }
                    },
                };
            }
        </script>
    @endpush
</x-app-layout>
