<x-app-layout>
    <div class="content pb-0">

        <div class="card mt-2 mx-2">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card-body">
                <h2 class="h4">Create Clinic</h2>
                <hr>
                <form method="POST" action="{{ route('clinics.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <h5 class="mb-3">Basic Information</h5>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Code</label>
                            <input name="code" class="form-control" value="{{ old('code') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Registration No.</label>
                            <input name="registration_number" class="form-control"
                                value="{{ old('registration_number') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Address Line 1</label>
                            <input name="address_line_1" class="form-control" value="{{ old('address_line_1') }}"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Address Line 2</label>
                            <input name="address_line_2" class="form-control" value="{{ old('address_line_2') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">City</label>
                            <input name="city" class="form-control" value="{{ old('city') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">State</label>
                            <input name="state" class="form-control" value="{{ old('state') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Country</label>
                            <input name="country" class="form-control" value="{{ old('country') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Postal Code</label>
                            <input name="postal_code" class="form-control" value="{{ old('postal_code') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Phone</label>
                            <input name="phone" class="form-control" value="{{ old('phone') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Email</label>
                            <input name="email" type="email" class="form-control" value="{{ old('email') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Website</label>
                            <input name="website" type="url" class="form-control" value="{{ old('website') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Timezone</label>
                            <input name="timezone" class="form-control" value="{{ old('timezone', 'UTC') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Currency</label>
                            <input name="currency" class="form-control" value="{{ old('currency', 'USD') }}" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Opening Time</label>
                            <input name="opening_time" type="time" class="form-control"
                                value="{{ old('opening_time') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Closing Time</label>
                            <input name="closing_time" type="time" class="form-control"
                                value="{{ old('closing_time') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive
                                </option>
                                <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>
                                    Suspended
                                </option>
                            </select>
                        </div>

                        <div class="col-12 mt-4">
                            <h5 class="mb-3">About & Services</h5>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">About Clinic</label>
                            <textarea name="about" class="form-control" rows="4" placeholder="Describe the clinic, mission, facilities...">{{ old('about') }}</textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Services</label>
                            <div id="servicesList">
                                <div class="input-group mb-2">
                                    <input type="text" name="services[]" class="form-control" placeholder="e.g., General Consultation" value="{{ old('services.0') }}">
                                    <button type="button" class="btn btn-outline-secondary" onclick="removeService(this)">Remove</button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addService()">Add Service</button>
                            <div class="form-text">Add each service offered by the clinic.</div>
                        </div>

                        <div class="col-12 mt-4">
                            <h5 class="mb-3">Branding & Images</h5>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Clinic Logo</label>
                            <div class="d-flex align-items-center gap-3">
                                <div class="border rounded p-2"
                                    style="width: 100px; height: 100px; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #f8f9fa;">
                                    <img id="logoPreview" src="#" alt="Logo Preview"
                                        style="max-width: 100%; max-height: 100%; display: none;">
                                    <span id="logoPlaceholder" class="text-muted small">No Logo</span>
                                </div>
                                <div>
                                    <input type="file" name="logo" class="form-control" accept="image/*,.svg"
                                        onchange="previewLogo(this)">
                                    <div class="form-text">Recommended size: 200x200px. Max: 2MB.</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <label class="form-label">Gallery Images</label>
                            <div class="border rounded p-3 bg-light">
                                <div class="text-center p-4 border-dashed rounded bg-white"
                                    style="border: 2px dashed #dee2e6; cursor: pointer;"
                                    onclick="document.getElementById('galleryInput').click()">
                                    <i class="ti ti-cloud-upload fs-2 text-muted"></i>
                                    <p class="mb-0 mt-2 text-muted">Click to browse images</p>
                                    <input type="file" name="gallery[]" id="galleryInput" class="d-none" multiple
                                        accept="image/*" onchange="handleGallerySelect(this)">
                                </div>
                                <div id="galleryPreview" class="row g-2 mt-3"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-primary" type="submit">Save</button>
                        <a class="btn btn-secondary" href="{{ route('clinics.index') }}">Cancel</a>
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
            wrapper.className = 'input-group mb-2';
            wrapper.innerHTML = `
                <input type="text" name="services[]" class="form-control" placeholder="e.g., General Consultation">
                <button type="button" class="btn btn-outline-secondary" onclick="removeService(this)">Remove</button>
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
