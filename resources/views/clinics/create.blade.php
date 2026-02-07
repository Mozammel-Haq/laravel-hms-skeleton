<x-app-layout>
    <div class="container-fluid mx-2 mt-2">
        <!-- Header -->


        <!-- Form Card -->
        <div class="card shadow-sm rounded-bottom mt-0">
            <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center bg-primary-subtle text-primary px-4 pt-4 pb-3 rounded-top shadow-sm mb-0">
            <div>
                <h5 class="fw-bold mb-1 text-primary">Create Clinic</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-dots mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('clinics.index') }}">Clinics</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create Clinic</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('clinics.index') }}" class="btn btn-sm btn-outline-primary">
                <i class="ti ti-arrow-left me-1"></i> Back to List
            </a>
        </div>
        <hr>
                <form method="POST" action="{{ route('clinics.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-4">
                        <!-- Left Column: Logo & Status -->
                        <div class="col-md-3 border-end">
                            <div class="text-center">
                                <label class="form-label fw-bold mb-3">Clinic Logo</label>
                                <div class="d-flex flex-column align-items-center">
                                    <div class="mb-3 position-relative border rounded p-2 bg-light" style="width: 140px; height: 140px; display: flex; align-items: center; justify-content: center;">
                                        <img id="logoPreview" src="#" alt="Logo Preview" style="max-width: 100%; max-height: 100%; display: none;">
                                        <span id="logoPlaceholder" class="text-muted small text-center"><i class="ti ti-photo fs-1"></i><br>No Logo</span>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary mb-2" onclick="document.querySelector('input[name=logo]').click()">
                                        Upload Logo
                                    </button>
                                    <input type="file" name="logo" class="d-none" accept="image/*,.svg" onchange="previewLogo(this)">
                                    <div class="text-muted small mb-3">Rec: 200x200px. Max: 2MB</div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="form-label fw-bold small">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select form-select-sm" required>
                                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                </select>
                            </div>
                        </div>

                        <!-- Right Column: Details -->
                        <div class="col-md-9">
                            <div class="card p-3">
                            <!-- Basic Information -->
                            <h5 class="text-primary fw-bold mb-3">Basic Information</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Name <span class="text-danger">*</span></label>
                                    <input name="name" class="form-control form-control" value="{{ old('name') }}" required placeholder="Clinic Name">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold">Code <span class="text-danger">*</span></label>
                                    <input name="code" class="form-control form-control" value="{{ old('code') }}" required placeholder="CLI-001">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold">Registration No.</label>
                                    <input name="registration_number" class="form-control form-control" value="{{ old('registration_number') }}" placeholder="REG-123">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">Phone</label>
                                    <input name="phone" class="form-control form-control" value="{{ old('phone') }}" placeholder="+880...">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">Email</label>
                                    <input name="email" type="email" class="form-control form-control" value="{{ old('email') }}" placeholder="info@clinic.com">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">Website</label>
                                    <input name="website" type="url" class="form-control form-control" value="{{ old('website') }}" placeholder="https://">
                                </div>
                            </div>
                            </div>
                            <div class="card p-3">
                            <!-- Location -->
                            <h5 class="text-primary fw-bold mb-3  ">Location</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Address Line 1 <span class="text-danger">*</span></label>
                                    <input name="address_line_1" class="form-control form-control" value="{{ old('address_line_1') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Address Line 2</label>
                                    <input name="address_line_2" class="form-control form-control" value="{{ old('address_line_2') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold">City <span class="text-danger">*</span></label>
                                    <input name="city" class="form-control form-control" value="{{ old('city') }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold">State</label>
                                    <input name="state" class="form-control form-control" value="{{ old('state') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold">Country <span class="text-danger">*</span></label>
                                    <input name="country" class="form-control form-control" value="{{ old('country') }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold">Postal Code</label>
                                    <input name="postal_code" class="form-control form-control" value="{{ old('postal_code') }}">
                                </div>
                            </div>
                            </div>
                            <div class="card p-3">
                            <!-- Operational Settings -->
                            <h5 class="text-primary fw-bold mb-3  ">Operational Settings</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold">Timezone <span class="text-danger">*</span></label>
                                    <input name="timezone" class="form-control form-control" value="{{ old('timezone', 'UTC') }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold">Currency <span class="text-danger">*</span></label>
                                    <input name="currency" class="form-control form-control" value="{{ old('currency', 'BDT') }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold">Opening Time</label>
                                    <input name="opening_time" type="time" class="form-control form-control" value="{{ old('opening_time') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold">Closing Time</label>
                                    <input name="closing_time" type="time" class="form-control form-control" value="{{ old('closing_time') }}">
                                </div>
                            </div>
                            </div>
                            <div class="card p-3">
                            <!-- About & Services -->
                            <h5 class="text-primary fw-bold mb-3  ">About & Services</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <label class="form-label small fw-semibold">About Clinic</label>
                                    <textarea name="about" class="form-control form-control" rows="3" placeholder="Describe the clinic, mission, facilities...">{{ old('about') }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-semibold">Services</label>
                                    <div id="servicesList">
                                        <div class="input-group input-group-sm mb-2">
                                            <input type="text" name="services[]" class="form-control form-control" placeholder="e.g., General Consultation" value="{{ old('services.0') }}">
                                            <button type="button" class="btn btn-outline-danger" onclick="removeService(this)"><i class="ti ti-x"></i></button>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addService()"><i class="ti ti-plus"></i> Add Service</button>
                                    <div class="form-text small">Add each service offered by the clinic.</div>
                                </div>
                            </div>
                            </div>
                            <div class="card p-3">
                             <!-- Gallery -->
                            <h5 class="text-primary fw-bold mb-3  ">Gallery</h5>
                            <div class="border rounded p-3 bg-light">
                                <div class="text-center p-4 border-dashed rounded bg-white"
                                    style="border: 2px dashed #dee2e6; cursor: pointer;"
                                    onclick="document.getElementById('galleryInput').click()">
                                    <i class="ti ti-cloud-upload fs-2 text-muted"></i>
                                    <p class="mb-0 mt-2 text-muted small">Click to browse images</p>
                                    <input type="file" name="gallery[]" id="galleryInput" class="d-none" multiple
                                        accept="image/*" onchange="handleGallerySelect(this)">
                                </div>
                                <div id="galleryPreview" class="row g-2 mt-3"></div>
                            </div>
                            </div>
                            <!-- Submit Buttons -->
                            <div class="mt-4 pt-3  text-end">
                                <a class="btn btn-secondary btn-sm" href="{{ route('clinics.index') }}">Cancel</a>
                                <button class="btn btn-primary btn-sm px-4" type="submit">Create Clinic</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Logo Preview
        function previewLogo(input) {
            const preview = document.getElementById('logoPreview');
            const placeholder = document.getElementById('logoPlaceholder');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    placeholder.style.display = 'none';
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.style.display = 'none';
                placeholder.style.display = 'block';
            }
        }

        // Gallery Management
        let selectedFiles = [];
        const galleryInput = document.getElementById('galleryInput');
        const galleryPreview = document.getElementById('galleryPreview');

        function handleGallerySelect(input) {
            if (input.files) {
                // Append new files to existing selection
                Array.from(input.files).forEach(file => {
                    // Prevent duplicates based on name and size (basic check)
                    if (!selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
                        selectedFiles.push(file);
                    }
                });

                updateGalleryInput();
                renderGallery();
            }
        }

        function updateGalleryInput() {
            const dt = new DataTransfer();
            selectedFiles.forEach(file => dt.items.add(file));
            galleryInput.files = dt.files;
        }

        function removeFile(index) {
            selectedFiles.splice(index, 1);
            updateGalleryInput();
            renderGallery();
        }

        function moveFile(index, direction) {
            if (direction === -1 && index > 0) {
                // Move Left
                const temp = selectedFiles[index];
                selectedFiles[index] = selectedFiles[index - 1];
                selectedFiles[index - 1] = temp;
            } else if (direction === 1 && index < selectedFiles.length - 1) {
                // Move Right
                const temp = selectedFiles[index];
                selectedFiles[index] = selectedFiles[index + 1];
                selectedFiles[index + 1] = temp;
            }
            updateGalleryInput();
            renderGallery();
        }

        function renderGallery() {
            galleryPreview.innerHTML = '';

            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                const col = document.createElement('div');
                col.className = 'col-md-2 col-4 gallery-item';

                // Create placeholder structure immediately
                col.innerHTML = `
                    <div class="position-relative border rounded overflow-hidden" style="height: 100px;">
                        <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                            <div class="spinner-border spinner-border-sm text-secondary" role="status"></div>
                        </div>

                        <!-- Controls Overlay -->
                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-between p-1" style="background: rgba(0,0,0,0.1); opacity: 0; transition: opacity 0.2s;">
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-sm btn-danger p-0 px-1" onclick="removeFile(${index})">&times;</button>
                            </div>
                            <div class="d-flex justify-content-center gap-1">
                                ${index > 0 ? `<button type="button" class="btn btn-sm btn-light p-0 px-1" onclick="moveFile(${index}, -1)">&larr;</button>` : ''}
                                ${index < selectedFiles.length - 1 ? `<button type="button" class="btn btn-sm btn-light p-0 px-1" onclick="moveFile(${index}, 1)">&rarr;</button>` : ''}
                            </div>
                        </div>
                    </div>
                `;

                // Hover effect for controls
                const wrapper = col.querySelector('.position-relative');
                const overlay = wrapper.querySelector('div[style*="opacity: 0"]');
                wrapper.addEventListener('mouseenter', () => overlay.style.opacity = '1');
                wrapper.addEventListener('mouseleave', () => overlay.style.opacity = '0');

                galleryPreview.appendChild(col);

                // Load image
                reader.onload = function(e) {
                    const imgDiv = col.querySelector('.w-100.h-100'); // The div with spinner
                    imgDiv.className = 'w-100 h-100'; // Remove centering classes if needed
                    imgDiv.innerHTML = `<img src="${e.target.result}" class="w-100 h-100 object-fit-cover">`;
                }
                reader.readAsDataURL(file);
            });
        }

        // Services add/remove
        function addService() {
            const list = document.getElementById('servicesList');
            const wrapper = document.createElement('div');
            wrapper.className = 'input-group input-group-sm mb-2';
            wrapper.innerHTML = `
                <input type="text" name="services[]" class="form-control form-control" placeholder="e.g., General Consultation">
                <button type="button" class="btn btn-outline-danger" onclick="removeService(this)"><i class="ti ti-x"></i></button>
            `;
            list.appendChild(wrapper);
        }
        function removeService(btn) {
            const item = btn.closest('.input-group');
            if (item && item.parentNode) {
                item.parentNode.removeChild(item);
            }
        }
    </script>
</x-app-layout>
