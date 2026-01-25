<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
            <h3 class="page-title mb-0">Profile Settings</h3>
        </div>

        @if (session('status') === 'profile-updated')
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Profile updated successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('status') === 'password-updated')
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Password updated successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Profile Information -->
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header bg-transparent">
                        <h5 class="card-title mb-0">Profile Information</h5>
                        <p class="card-text text-muted small mt-1">Update your account's profile information and email
                            address.</p>
                    </div>
                    <div class="card-body">
                        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                            @csrf
                        </form>

                        <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('patch')

                            <div class="mb-3">
                                <label for="profile_photo" class="form-label">Profile Photo</label>
                                <div class="d-flex align-items-center gap-3">
                                    {{-- Image Preview --}}
                                    <img id="profilePhotoPreview" src="{{ $user->profile_photo_url ?? '#' }}"
                                        alt="{{ $user->name }}"
                                        class="rounded-circle object-fit-cover {{ $user->profile_photo_url ? '' : 'd-none' }}"
                                        width="80" height="80">

                                    {{-- Fallback Placeholder --}}
                                    <div id="profilePhotoPlaceholder"
                                        class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center {{ $user->profile_photo_url ? 'd-none' : '' }}"
                                        style="width: 80px; height: 80px; font-size: 2rem;">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>

                                    <input type="file" name="profile_photo" id="profile_photo" class="form-control"
                                        onchange="previewProfilePhoto(this)">
                                </div>
                                @error('profile_photo')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name" id="name" class="form-control"
                                    value="{{ old('name', $user->name) }}" required autocomplete="name">
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control"
                                    value="{{ old('email', $user->email) }}" required autocomplete="username">
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror

                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                    <div class="mt-2">
                                        <p class="text-muted small mb-1">
                                            Your email address is unverified.
                                            <button form="send-verification"
                                                class="btn btn-link p-0 align-baseline text-decoration-none">
                                                Click here to re-send the verification email.
                                            </button>
                                        </p>

                                        @if (session('status') === 'verification-link-sent')
                                            <p class="text-success small fw-medium">
                                                A new verification link has been sent to your email address.
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="d-flex align-items-center gap-3">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Update Password -->
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header bg-transparent">
                        <h5 class="card-title mb-0">Update Password</h5>
                        <p class="card-text text-muted small mt-1">Ensure your account is using a long, random password
                            to stay secure.</p>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('password.update') }}">
                            @csrf
                            @method('put')

                            <div class="mb-3">
                                <label for="update_password_current_password" class="form-label">Current
                                    Password</label>
                                <input type="password" name="current_password" id="update_password_current_password"
                                    class="form-control" autocomplete="current-password">
                                @error('current_password', 'updatePassword')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="update_password_password" class="form-label">New Password</label>
                                <input type="password" name="password" id="update_password_password"
                                    class="form-control" autocomplete="new-password">
                                @error('password', 'updatePassword')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="update_password_password_confirmation" class="form-label">Confirm
                                    Password</label>
                                <input type="password" name="password_confirmation"
                                    id="update_password_password_confirmation" class="form-control"
                                    autocomplete="new-password">
                                @error('password_confirmation', 'updatePassword')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex align-items-center gap-3">
                                <button type="submit" class="btn btn-primary">Update Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Delete Account -->
            <div class="col-12">
                <div class="card border-danger">
                    <div class="card-header bg-danger bg-opacity-10">
                        <h5 class="card-title text-danger mb-0">Delete Account</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">
                            Once your account is deleted, all of its resources and data will be permanently deleted.
                            Before deleting your account, please download any data or information that you wish to
                            retain.
                        </p>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                            data-bs-target="#confirmUserDeletionModal">
                            Delete Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1"
        aria-labelledby="confirmUserDeletionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmUserDeletionModalLabel">Are you sure you want to delete
                            your account?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small">
                            Once your account is deleted, all of its resources and data will be permanently deleted.
                            Please enter your password to confirm you would like to permanently delete your account.
                        </p>

                        <div class="mb-3">
                            <label for="password" class="form-label visually-hidden">Password</label>
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Password" required>
                            @error('password', 'userDeletion')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($errors->userDeletion->isNotEmpty())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var myModal = new bootstrap.Modal(document.getElementById('confirmUserDeletionModal'));
                myModal.show();
            });
        </script>
    @endif

    <script>
        function previewProfilePhoto(input) {
            const preview = document.getElementById('profilePhotoPreview');
            const placeholder = document.getElementById('profilePhotoPlaceholder');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                    if (placeholder) {
                        placeholder.classList.add('d-none');
                    }
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-app-layout>
