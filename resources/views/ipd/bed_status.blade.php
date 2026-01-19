<x-app-layout>
    <div class="container-fluid">

        {{-- TOP STATS (BOOTSTRAP ONLY – NO LEGEND) --}}
        <div class="row g-3 mb-2">

            <div class="row g-3">
                {{-- BED DASHBOARD --}}
                <div class="col-xl-6 col-md-12">
                    <div class="card shadow-sm rounded-2">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="mb-1">Bed Status</h5>
                                <p class="text-muted mb-0">Real-time inpatient availability overview</p>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('ipd.index') }}" class="btn btn-outline-primary btn-sm">Go to IPD
                                    Dashboard</a>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- AVAILABLE BEDS --}}
                <div class="col-xl-3 col-md-6">
                    <div class="card border-success shadow-sm rounded-2 position-relative">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2 justify-content-between">
                                <span class="avatar bg-success rounded-circle p-2"><i
                                        class="ti ti-bed fs-24"></i></span>
                                <div class="text-end">
                                    <span
                                        class="badge px-2 py-1 fs-12 fw-medium d-inline-flex mb-1 bg-success">Available</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="mb-1 text-muted">Available Beds</p>
                                    <h3 class="fw-bold text-success mb-0">{{ $bedsAvailable }}</h3>
                                </div>
                                <i class="ti ti-bed fs-2 text-success opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- OCCUPIED BEDS --}}
                <div class="col-xl-3 col-md-6">
                    <div class="card border-danger shadow-sm rounded-2 position-relative">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2 justify-content-between">
                                <span class="avatar bg-danger rounded-circle p-2"><i class="ti ti-bed fs-24"></i></span>
                                <div class="text-end">
                                    <span
                                        class="badge px-2 py-1 fs-12 fw-medium d-inline-flex mb-1 bg-danger">Occupied</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="mb-1 text-muted">Occupied Beds</p>
                                    <h3 class="fw-bold text-danger mb-0">{{ $bedsOccupied }}</h3>
                                </div>
                                <i class="ti ti-bed fs-2 text-danger opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>



            </div>


        </div>


        <div class="card"
            x-data='bedStatusMatrix({ wards: @json($wards), bedAdmissions: @json($bedAdmissions) })'>
            <div class="card-body">

                {{-- FILTERS (UNCHANGED) --}}
                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <select class="form-select form-select-sm" x-model.number="selectedWardId"
                            @change="onWardChange">
                            <template x-for="ward in wards" :key="ward.id">
                                <option :value="ward.id" x-text="ward.name"></option>
                            </template>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select class="form-select form-select-sm" x-model.number="selectedRoomId">
                            <template x-for="room in rooms" :key="room.id">
                                <option :value="room.id" x-text="room.room_number"></option>
                            </template>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <button type="button" class="btn btn-outline-primary btn-sm" @click="toggleReorder"> <i
                                class="ti ti-layout-grid"></i> <span
                                x-text="reorderMode ? 'Finish Layout Edit' : 'Edit Room Layout'"></span> </button>
                    </div>
                </div>

                <div class="room-floor-wrapper">
                    <div class="room-floor">

                        <template x-for="bed in beds" :key="bed.id">
                            <div class="bed-slot" :class="bedSlotClass(bed)"
                                @click="!reorderMode && bed.status !== 'maintenance' && openBedModal(bed)"
                                draggable="true" @dragstart="onDragStart(bed)" @dragover.prevent @drop="onDrop(bed)">
                                <div class="bed-frame">
                                    <i class="ti ti-bed"></i>
                                    <span class="bed-name" x-text="bed.bed_number"></span>
                                </div>

                            </div>
                        </template>

                        <div x-show="beds.length === 0" class="text-muted small text-center w-100">
                            No beds configured for this room
                        </div>

                    </div>
                </div>

                <template x-if="showModal">
                    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);"
                        @click.self="showModal = false" @keydown.escape.window="showModal = false">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        <span x-show="modalAdmission">Bed Occupied</span>
                                        <span x-show="!modalAdmission">Bed Available</span>
                                    </h5>
                                    <button type="button" class="btn-close" @click="showModal = false"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-2">
                                        <div class="fw-semibold" x-text="modalBed ? modalBed.bed_number : ''"></div>
                                        <div class="text-muted small" x-show="modalAdmission">
                                            <span x-text="'Patient: ' + modalAdmission.patient_name"></span>
                                            <span x-text="' (' + modalAdmission.patient_code + ')'"></span>
                                        </div>
                                    </div>
                                    <template x-if="modalAdmission">
                                        <div class="mb-2">
                                            <div class="text-muted small">
                                                <span x-text="'Admission ID: ' + modalAdmission.id"></span>
                                            </div>
                                            <div class="text-muted small">
                                                <span
                                                    x-text="'Admission Date: ' + modalAdmission.admission_date"></span>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="!modalAdmission">
                                        <div class="text-muted small">
                                            This bed is currently available. You can admit a patient directly into
                                            this bed.
                                        </div>
                                    </template>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        @click="showModal = false">Close</button>
                                    <template x-if="modalAdmission">
                                        <a class="btn btn-primary btn-sm" :href="modalAdmission.ipd_show_url">
                                            View Admission
                                        </a>
                                    </template>
                                    <template x-if="!modalAdmission">
                                        <a class="btn btn-success btn-sm"
                                            :href="'{{ route('ipd.create') }}?bed_id=' + (modalBed ? modalBed.id : '')">
                                            Admit Patient
                                        </a>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                {{-- SUMMARY TABLE (UNCHANGED) --}}
                <div class="table-responsive mt-4">
                    <table class="table table-hover align-middle datatable">
                        <thead>
                            <tr>
                                <th>Ward</th>
                                <th>Rooms</th>
                                <th>Total Beds</th>
                                <th>Available</th>
                                <th>Occupied</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($wards as $ward)
                                @php $bedsCollection = $ward->rooms->flatMap->beds; @endphp
                                <tr>
                                    <td>{{ $ward->name }}</td>
                                    <td>{{ $ward->rooms->count() }}</td>
                                    <td>{{ $bedsCollection->count() }}</td>
                                    <td><span
                                            class="badge bg-success">{{ $bedsCollection->where('status', 'available')->count() }}</span>
                                    </td>
                                    <td><span
                                            class="badge bg-danger">{{ $bedsCollection->where('status', 'occupied')->count() }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    {{-- STYLES --}}
    @push('styles')
        <style>
            .room-floor-wrapper {
                padding: 20px;
                background: #f8f9fa;
                border-radius: 16px;
                border: 2px solid #dee2e6;
            }

            .room-floor {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
                gap: 18px;
            }


            .bed-slot {
                display: flex;
                justify-content: center;
                cursor: pointer;
            }

            .bed-frame {
                width: 100%;
                height: 70px;
                border-radius: 12px;
                border: 2px solid #dee2e6;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                font-size: 28px;
                background: #ffffff;
                box-shadow: 0 6px 14px rgba(0, 0, 0, .08);
                transition: transform .15s ease;
                @media (max-width: 767px) {
                    height: 60px;
                    font-size: 24px;
                }
            }

            .bed-frame:hover {
                transform: translateY(-3px);
            }

            .bed-available .bed-frame {
                background: #e6f4ea;
                border-color: #198754;
            }

            .bed-occupied .bed-frame {
                background: #fdecec;
                border-color: #dc3545;
            }

            .bed-maintenance .bed-frame {
                background: #e9ecef;
                border-color: #6c757d;
                opacity: .7;
            }

            .bed-empty .bed-frame {
                background: #f1f3f5;
                border-style: dashed;
                opacity: .4;
                cursor: not-allowed;
            }

            .bed-name {
                font-size: 16px;
                font-weight: 600;
                color: #495057;
                margin-top: 4px;
                line-height: 1;
                white-space: nowrap;
            }
        </style>
    @endpush

    {{-- ALPINE --}}
    @push('scripts')
        <script>
            function bedStatusMatrix(config) {
                return {
                    wards: config.wards,
                    bedAdmissions: config.bedAdmissions || {},
                    selectedWardId: null,
                    selectedRoomId: null,
                    reorderMode: false,
                    draggingBedId: null,
                    showModal: false,
                    modalBed: null,
                    modalAdmission: null,

                    init() {
                        const ward = this.wards.find(w => w.rooms?.length);
                        if (ward) {
                            this.selectedWardId = ward.id;
                            this.selectedRoomId = ward.rooms[0].id;
                        }
                    },

                    get rooms() {
                        return this.wards.find(w => w.id === this.selectedWardId)?.rooms || [];
                    },

                    get beds() {
                        return this.rooms.find(r => r.id === this.selectedRoomId)?.beds || [];
                    },

                    bedSlotClass(bed) {
                        return {
                            'bed-available': bed.status === 'available',
                            'bed-occupied': bed.status === 'occupied',
                            'bed-maintenance': bed.status === 'maintenance',
                        };
                    },

                    toggleReorder() {
                        this.reorderMode = !this.reorderMode;
                    },

                    onDragStart(bed) {
                        if (!this.reorderMode) return;
                        this.draggingBedId = bed.id;
                    },

                    onDrop(targetBed) {
                        if (!this.reorderMode) return;
                        const beds = this.beds;
                        const from = beds.findIndex(b => b.id === this.draggingBedId);
                        const to = beds.findIndex(b => b.id === targetBed.id);
                        if (from === -1 || to === -1 || from === to) return;
                        const moved = beds.splice(from, 1)[0];
                        beds.splice(to, 0, moved);
                        this.draggingBedId = null;
                        this.saveOrder(beds);
                    },

                    openBedModal(bed) {
                        this.modalBed = bed;
                        this.modalAdmission = this.bedAdmissions[bed.id] || null;
                        this.showModal = true;
                    },

                    saveOrder(beds) {
                        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        if (!token) return;
                        const ids = beds.map(b => b.id);
                        fetch("{{ route('ipd.beds.reorder') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                room_id: this.selectedRoomId,
                                order: ids,
                            }),
                        });
                    },
                }
            }
        </script>
    @endpush
</x-app-layout>
