<x-app-layout>
    <div class="container-fluid mx-2 mt-2">
        <div class="card border-0 shadow-sm rounded-bottom rounded-0">
            <div class="card-body p-2">
                <form action="{{ route('ipd.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-8">
                            <div
                                class="d-flex mb-2 justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-4 pb-3 rounded-top">
                                <div>
                                    <h5 class="fw-bold mb-1 text-primary">Admit Patient</h5>
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb breadcrumb-dots mb-0">
                                            <li class="breadcrumb-item"><a href="{{ route('ipd.index') }}">IPD</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">Admit Patient</li>
                                        </ol>
                                    </nav>
                                </div>
                                <a href="{{ route('ipd.index') }}" class="btn btn-sm btn-outline-primary">
                                    <i class="ti ti-arrow-left me-1"></i> Back to List
                                </a>
                            </div>
                            <hr>
                            <h5 class="card-title mb-3 pb-2 border-bottom">Admission Information</h5>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label small text-muted">Patient <span
                                            class="text-danger">*</span></label>
                                    <select id="patient_id" name="patient_id"
                                        class="form-select form-select-sm select2-patient" required>
                                        <option value="">Select Patient</option>
                                        @if (isset($patients))
                                            @foreach ($patients as $patient)
                                                <option value="{{ $patient->id }}" selected>
                                                    {{ $patient->name }} ({{ $patient->patient_code }})
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <x-input-error :messages="$errors->get('patient_id')" class="mt-2" />
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small text-muted">Attending Doctor <span
                                            class="text-danger">*</span></label>
                                    <select id="doctor_id" name="doctor_id" class="form-select form-select-sm" required>
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
                                    <label class="form-label small text-muted">Admission Date <span
                                            class="text-danger">*</span></label>
                                    <input id="admission_date" type="datetime-local" name="admission_date"
                                        class="form-control form-control-sm"
                                        value="{{ old('admission_date', now()->format('Y-m-d\TH:i')) }}" required>
                                    <x-input-error :messages="$errors->get('admission_date')" class="mt-2" />
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label small text-muted">Admission Notes / Reason</label>
                                    <textarea id="admission_reason" name="admission_reason" class="form-control form-control-sm" rows="3"
                                        placeholder="Enter reason for admission and initial notes...">{{ old('admission_reason') }}</textarea>
                                    <x-input-error :messages="$errors->get('admission_reason')" class="mt-2" />
                                </div>
                            </div>

                            <h5 class="card-title mb-4 pb-2 border-bottom">Financials</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small text-muted">Admission Fee</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">৳</span>
                                        <input id="admission_fee" type="number" name="admission_fee"
                                            class="form-control form-control-sm" value="{{ old('admission_fee', 0) }}"
                                            min="0" step="0.01">
                                    </div>
                                    <x-input-error :messages="$errors->get('admission_fee')" class="mt-2" />
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small text-muted">Deposit Amount</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">৳</span>
                                        <input id="deposit_amount" type="number" name="deposit_amount"
                                            class="form-control form-control-sm" value="{{ old('deposit_amount', 0) }}"
                                            min="0" step="0.01">
                                    </div>
                                    <x-input-error :messages="$errors->get('deposit_amount')" class="mt-2" />
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small text-muted">Discount (on Fee)</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">৳</span>
                                        <input type="number" name="discount" class="form-control form-control-sm"
                                            step="0.01" min="0" value="0">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small text-muted">Tax % (on Fee)</label>
                                    <div class="input-group input-group-sm">
                                        <input type="number" name="tax" class="form-control form-control-sm"
                                            step="0.01" min="0" value="0">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                                    <!-- Right Column: Bed Selection -->
            <div class="col-lg-4">
                <script>
                    window.bedMatrix = function(config) {
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
                                let classes = ['btn-outline-primary'];
                                if (bed.status === 'available') {
                                    classes = ['btn-outline-success'];
                                } else if (bed.status === 'occupied') {
                                    classes = ['btn-outline-danger'];
                                } else if (bed.status === 'maintenance') {
                                    classes = ['btn-outline-primary', 'opacity-50'];
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

                    // Configuration object to avoid HTML attribute quoting issues
                    window.ipdBedsConfig = {
                        wards: @json($wards),
                        initialBedId: Number(@json(old('bed_id') ?? request('bed_id'))) || null
                    };
                </script>

                <div class="card shadow-sm border-0 mb-4" x-data="window.bedMatrix(window.ipdBedsConfig)">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <div class="fw-semibold">Bed Selection</div>
                                <div class="text-muted small">Choose an available bed</div>
                            </div>
                            <a href="{{ route('ipd.bed_status') }}" class="btn btn-sm btn-outline-primary">
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
                                            x-text="room.room_number + ' (' + (room.room_type || '') + ')'"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="d-flex align-items-center gap-1">
                                <span class="badge bg-success-subtle text-success"><i class="ti ti-bed"></i></span>
                                <span class="small text-muted">Available</span>
                            </div>
                            <div class="d-flex align-items-center gap-1">
                                <span class="badge bg-danger-subtle text-danger"><i class="ti ti-bed"></i></span>
                                <span class="small text-muted">Occupied</span>
                            </div>
                            <div class="d-flex align-items-center gap-1">
                                <span class="badge bg-secondary-subtle text-secondary"><i
                                        class="ti ti-tools"></i></span>
                                <span class="small text-muted">Maintenance</span>
                            </div>
                        </div>

                        <div class="border rounded p-2 bg-light" style="max-height: 300px; overflow-y: auto;">
                            <div class="d-flex flex-wrap gap-2 justify-content-center">
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
                                <div x-show="beds.length === 0" class="text-muted small w-100 text-center py-4">
                                    No beds found for this room.
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="bed_id" id="bed_id" value="{{ old('bed_id') }}">
                        <x-input-error :messages="$errors->get('bed_id')" class="mt-2" />
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary" id="admit-button">
                        <i class="ti ti-check me-1"></i> Admit Patient
                    </button>
                    <a href="{{ route('ipd.index') }}" class="btn btn-light">Cancel</a>
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
                        theme: 'bootstrap-5',
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
                        width: '100%',
                        language: {
                            noResults: function() {
                                return "No patients found";
                            }
                        }
                    });
                });
            </script>
        @endpush
</x-app-layout>
