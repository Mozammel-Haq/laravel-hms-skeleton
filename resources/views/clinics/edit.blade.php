<x-app-layout>
    <div class="content pb-0">



        <div class="card mt-2 mx-2">
            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <h2 class="h4">Edit Clinic</h2>
                <hr>
                <form method="POST" action="{{ route('clinics.update', $clinic) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-12">
                            <h5 class="mb-3">Basic Information</h5>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input name="name" class="form-control" value="{{ old('name', $clinic->name) }}"
                                required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Code</label>
                            <input name="code" class="form-control" value="{{ old('code', $clinic->code) }}"
                                required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Registration No.</label>
                            <input name="registration_number" class="form-control"
                                value="{{ old('registration_number', $clinic->registration_number) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Address Line 1</label>
                            <input name="address_line_1" class="form-control"
                                value="{{ old('address_line_1', $clinic->address_line_1) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Address Line 2</label>
                            <input name="address_line_2" class="form-control"
                                value="{{ old('address_line_2', $clinic->address_line_2) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">City</label>
                            <input name="city" class="form-control" value="{{ old('city', $clinic->city) }}"
                                required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">State</label>
                            <input name="state" class="form-control" value="{{ old('state', $clinic->state) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Country</label>
                            <input name="country" class="form-control" value="{{ old('country', $clinic->country) }}"
                                required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Postal Code</label>
                            <input name="postal_code" class="form-control"
                                value="{{ old('postal_code', $clinic->postal_code) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Phone</label>
                            <input name="phone" class="form-control" value="{{ old('phone', $clinic->phone) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Email</label>
                            <input name="email" type="email" class="form-control"
                                value="{{ old('email', $clinic->email) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Website</label>
                            <input name="website" type="url" class="form-control"
                                value="{{ old('website', $clinic->website) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Timezone</label>
                            <input name="timezone" class="form-control"
                                value="{{ old('timezone', $clinic->timezone) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Currency</label>
                            <input name="currency" class="form-control"
                                value="{{ old('currency', $clinic->currency) }}" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Opening Time</label>
                            <input name="opening_time" type="time" class="form-control"
                                value="{{ old('opening_time', $clinic->opening_time) }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Closing Time</label>
                            <input name="closing_time" type="time" class="form-control"
                                value="{{ old('closing_time', $clinic->closing_time) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="active"
                                    {{ old('status', $clinic->status) === 'active' ? 'selected' : '' }}>
                                    Active</option>
                                <option value="inactive"
                                    {{ old('status', $clinic->status) === 'inactive' ? 'selected' : '' }}>Inactive
                                </option>
                                <option value="suspended"
                                    {{ old('status', $clinic->status) === 'suspended' ? 'selected' : '' }}>Suspended
                                </option>
                            </select>
                        </div>

                        <div class="col-12 mt-4">
                            <h5 class="mb-3">About & Services</h5>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">About Clinic</label>
                            <textarea name="about" class="form-control" rows="4" placeholder="Describe the clinic, mission, facilities...">{{ old('about', $clinic->about) }}</textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Services</label>
                            <div id="servicesList">
                                @php
                                    $services = old('services', $clinic->services ?? []);
                                    if (empty($services)) {
                                        $services = [''];
                                    }
                                @endphp
                                @foreach ($services as $service)
                                    <div class="input-group mb-2">
                                        <input type="text" name="services[]" class="form-control" placeholder="e.g., General Consultation" value="{{ $service }}">
                                        <button type="button" class="btn btn-outline-secondary" onclick="removeService(this)">Remove</button>
                                    </div>
                                @endforeach
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
                                    @if ($clinic->logo_path)
                                        <img id="logoPreview" src="{{ Storage::url($clinic->logo_path) }}"
                                            alt="Logo Preview" style="max-width: 100%; max-height: 100%;">
                                        <span id="logoPlaceholder" class="text-muted small" style="display: none;">No
                                            Logo</span>
                                    @else
                                        <img id="logoPreview" src="#" alt="Logo Preview"
                                            style="max-width: 100%; max-height: 100%; display: none;">
                                        <span id="logoPlaceholder" class="text-muted small">No Logo</span>
                                    @endif
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

                            <div class="mb-2 text-muted small">Use arrow buttons to reorder images.</div>

                            <div id="existingGallery" class="row g-2 mb-3">
                                @foreach ($clinic->images as $index => $image)
                                    <div class="col-md-2 col-4 gallery-item" data-id="{{ $image->id }}">
                                        <div class="position-relative border rounded overflow-hidden group"
                                            style="height: 100px;">
                                            <img src="{{ Storage::url($image->image_path) }}"
                                                class="w-100 h-100 object-fit-cover">

                                            <!-- Controls Overlay -->
                                            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-between p-1"
                                                style="background: rgba(0,0,0,0.1); opacity: 0; transition: opacity 0.2s;">
                                                <div class="d-flex justify-content-end">
                                                    <button type="button" class="btn btn-sm btn-danger p-0 px-1"
                                                        onclick="deleteImage(this, {{ $image->id }})">&times;</button>
                                                </div>
                                                <div class="d-flex justify-content-center gap-1">
                                                    <button type="button"
                                                        class="btn btn-sm btn-light p-0 px-1 prev-btn"
                                                        onclick="moveExisting(this, -1)">&larr;</button>
                                                    <button type="button"
                                                        class="btn btn-sm btn-light p-0 px-1 next-btn"
                                                        onclick="moveExisting(this, 1)">&rarr;</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <input type="hidden" name="gallery_order" id="galleryOrder">

                            <div class="border rounded p-3 bg-light">
                                <div class="text-center p-4 border-dashed rounded bg-white"
                                    style="border: 2px dashed #dee2e6; cursor: pointer;"
                                    onclick="document.getElementById('galleryInput').click()">
                                    <i class="ti ti-cloud-upload fs-2 text-muted"></i>
                                    <p class="mb-0 mt-2 text-muted">Click to browse NEW images</p>
                                    <input type="file" name="gallery[]" id="galleryInput" class="d-none" multiple
                                        accept="image/*" onchange="handleGallerySelect(this)">
                                </div>
                                <div id="galleryPreview" class="row g-2 mt-3"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-primary" type="submit">Update</button>
                        <a class="btn btn-secondary" href="{{ route('clinics.index') }}">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
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
            }
        }

        // New Gallery Management (for new uploads)
        let selectedFiles = [];
        const galleryInput = document.getElementById('galleryInput');
        const galleryPreview = document.getElementById('galleryPreview');

        function handleGallerySelect(input) {
            if (input.files) {
                Array.from(input.files).forEach(file => {
                    if (!selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
                        selectedFiles.push(file);
                    }
                });
                updateGalleryInput();
                renderNewGallery();
            }
        }

        function updateGalleryInput() {
            const dt = new DataTransfer();
            selectedFiles.forEach(file => dt.items.add(file));
            galleryInput.files = dt.files;
        }

        function removeNewFile(index) {
            selectedFiles.splice(index, 1);
            updateGalleryInput();
            renderNewGallery();
        }

        function moveNewFile(index, direction) {
            if (direction === -1 && index > 0) {
                const temp = selectedFiles[index];
                selectedFiles[index] = selectedFiles[index - 1];
                selectedFiles[index - 1] = temp;
            } else if (direction === 1 && index < selectedFiles.length - 1) {
                const temp = selectedFiles[index];
                selectedFiles[index] = selectedFiles[index + 1];
                selectedFiles[index + 1] = temp;
            }
            updateGalleryInput();
            renderNewGallery();
        }

        function renderNewGallery() {
            galleryPreview.innerHTML = '';

            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                const col = document.createElement('div');
                col.className = 'col-md-2 col-4 new-gallery-item';

                col.innerHTML = `
                    <div class="position-relative border rounded overflow-hidden" style="height: 100px;">
                        <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                            <div class="spinner-border spinner-border-sm text-secondary" role="status"></div>
                        </div>

                        <!-- Controls Overlay -->
                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-between p-1" style="background: rgba(0,0,0,0.1); opacity: 0; transition: opacity 0.2s;">
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-sm btn-danger p-0 px-1" onclick="removeNewFile(${index})">&times;</button>
                            </div>
                            <div class="d-flex justify-content-center gap-1">
                                ${index > 0 ? `<button type="button" class="btn btn-sm btn-light p-0 px-1" onclick="moveNewFile(${index}, -1)">&larr;</button>` : ''}
                                ${index < selectedFiles.length - 1 ? `<button type="button" class="btn btn-sm btn-light p-0 px-1" onclick="moveNewFile(${index}, 1)">&rarr;</button>` : ''}
                            </div>
                        </div>
                    </div>
                `;

                // Hover effect
                const wrapper = col.querySelector('.position-relative');
                const overlay = wrapper.querySelector('div[style*="opacity: 0"]');
                wrapper.addEventListener('mouseenter', () => overlay.style.opacity = '1');
                wrapper.addEventListener('mouseleave', () => overlay.style.opacity = '0');

                galleryPreview.appendChild(col);

                reader.onload = function(e) {
                    const imgDiv = col.querySelector('.w-100.h-100');
                    imgDiv.className = 'w-100 h-100';
                    imgDiv.innerHTML = `<img src="${e.target.result}" class="w-100 h-100 object-fit-cover">`;
                }
                reader.readAsDataURL(file);
            });
        }

        // Existing Gallery Management
        function deleteImage(btn, id) {
            if (!confirm('Are you sure?')) return;

            fetch(`{{ url('clinics/images') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        btn.closest('.gallery-item').remove();
                        updateExistingOrder();
                    }
                });
        }

        function moveExisting(btn, direction) {
            const currentItem = btn.closest('.gallery-item');
            const container = document.getElementById('existingGallery');

            if (direction === -1) {
                // Move Left
                const prevItem = currentItem.previousElementSibling;
                if (prevItem) {
                    container.insertBefore(currentItem, prevItem);
                }
            } else {
                // Move Right
                const nextItem = currentItem.nextElementSibling;
                if (nextItem) {
                    container.insertBefore(nextItem, currentItem);
                }
            }
            updateExistingOrder();
        }

        function updateExistingOrder() {
            const items = document.querySelectorAll('#existingGallery .gallery-item');
            const order = {};

            items.forEach((item, index) => {
                order[index] = item.dataset.id;

                // Update button visibility based on new position
                const prevBtn = item.querySelector('.prev-btn');
                const nextBtn = item.querySelector('.next-btn');

                if (prevBtn) prevBtn.style.display = index === 0 ? 'none' : 'inline-block';
                if (nextBtn) nextBtn.style.display = index === items.length - 1 ? 'none' : 'inline-block';
            });

            document.getElementById('galleryOrder').value = JSON.stringify(order);
        }

        // Initialize button visibility for existing items
        document.addEventListener('DOMContentLoaded', () => {
            const items = document.querySelectorAll('#existingGallery .gallery-item');
            items.forEach((item, index) => {
                const wrapper = item.querySelector('.position-relative');
                const overlay = wrapper.querySelector('div[style*="opacity: 0"]');
                wrapper.addEventListener('mouseenter', () => overlay.style.opacity = '1');
                wrapper.addEventListener('mouseleave', () => overlay.style.opacity = '0');

                // Initial button state
                const prevBtn = item.querySelector('.prev-btn');
                const nextBtn = item.querySelector('.next-btn');
                if (prevBtn) prevBtn.style.display = index === 0 ? 'none' : 'inline-block';
                if (nextBtn) nextBtn.style.display = index === items.length - 1 ? 'none' : 'inline-block';
            });
        });

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
