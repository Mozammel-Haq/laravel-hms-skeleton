<x-app-layout>
    <div class="container-fluid">


        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card border-0 mt-2"
                    x-data='bedAssignMatrix({
                    wards: @json($wards),
                    currentBedId: {{ $admission->current_bed_id ? (int) $admission->current_bed_id : 'null' }}
                })'>
                    <div class="card-body px-3 py-2">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h4 class="mb-1">Assign/Transfer Bed</h4>
                                <p class="text-muted mb-0">Choose an available bed for this admission</p>
                            </div>
                            <a href="{{ route('ipd.show', $admission->id) }}" class="btn btn-outline-secondary">
                                <i class="ti ti-arrow-left me-1"></i> Back to Admission
                            </a>
                        </div>
                        <form method="POST" action="{{ route('ipd.store-bed', $admission->id) }}">
                            @csrf
                            <input type="hidden" name="bed_id" id="bed_id" value="">
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
                                <div class="d-flex align-items-center gap-1">
                                    <span class="badge bg-primary-subtle text-primary">
                                        <i class="ti ti-circle-check"></i>
                                    </span>
                                    <span class="small text-muted">Current Bed</span>
                                </div>
                            </div>
                            <div class="border rounded p-2 mb-3" style="max-height: 320px; overflow-y: auto;">
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
                                            <span
                                                class="position-absolute bottom-0 start-50 translate-middle-x badge rounded-pill bg-primary-subtle text-primary"
                                                x-show="isCurrent(bed)">
                                                Current
                                            </span>
                                        </button>
                                    </template>
                                    <div x-show="beds.length === 0" class="text-muted small">
                                        No beds found for this room.
                                    </div>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('bed_id')" class="mt-2" />
                            <div class="d-flex justify-content-end gap-2 mt-3">
                                <a class="btn btn-light" href="{{ route('ipd.show', $admission) }}">Cancel</a>
                                <button class="btn btn-primary" type="submit" id="assign-button">
                                    <i class="ti ti-check me-1"></i> Assign Bed
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent">
                        <h5 class="card-title mb-0">Patient Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar avatar-md me-3">
                                <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                    {{ substr($admission->patient->name, 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $admission->patient->name }}</h6>
                                <div class="text-muted small">{{ $admission->patient->patient_code }}</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="text-muted small text-uppercase fw-bold mb-1">Attending Doctor</div>
                            <div>Dr. {{ $admission->doctor?->user?->name ?? 'Deleted Doctor' }}</div>
                        </div>

                        <div class="mb-3">
                            <div class="text-muted small text-uppercase fw-bold mb-1">Admission Date</div>
                            <div>{{ $admission->created_at }}</div>
                        </div>

                        <div>
                            <div class="text-muted small text-uppercase fw-bold mb-1">Current Status</div>
                            <span class="badge bg-{{ $admission->status === 'admitted' ? 'success' : 'secondary' }}">
                                {{ ucfirst($admission->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function bedAssignMatrix(config) {
            return {
                wards: config.wards || [],
                selectedWardId: null,
                selectedRoomId: null,
                currentBedId: config.currentBedId,
                selectedBedId: null,
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
                    if (this.currentBedId) {
                        for (const ward of this.wards) {
                            for (const room of ward.rooms || []) {
                                for (const bed of room.beds || []) {
                                    if (bed.id === this.currentBedId) {
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
                isCurrent(bed) {
                    return this.currentBedId && this.currentBedId === bed.id;
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
                    if (this.isCurrent(bed)) {
                        classes.push('border-primary');
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
                    const button = document.getElementById('assign-button');
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
</x-app-layout>
