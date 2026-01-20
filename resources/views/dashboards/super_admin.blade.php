<x-app-layout>
    <div class="container-fluid mx-2">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="page-title mb-0">Super Admin Dashboard</h3>
            <div class="text-muted">{{ now()->format('l, d M Y') }}</div>
        </div>

        <div class="alert alert-info">
            <i class="ti ti-info-circle me-2"></i>
            Welcome to the Super Admin Dashboard. Use the sidebar to manage clinics, subscriptions, and system-wide
            settings.
        </div>

        <div class="row g-4 mb-4">
            <!-- Manage Clinics Card -->
            <div class="col-md-4">
                <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-primary"
                    data-bs-theme="light,dark">
                    <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-circle-sa-1" x="0" y="0" width="40" height="40"
                                    patternUnits="userSpaceOnUse">
                                    <circle cx="20" cy="20" r="2" fill="var(--primary-color)"
                                        fill-opacity="0.2" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#pattern-circle-sa-1)" />
                        </svg>
                    </div>
                    <div class="position-absolute top-0 end-0 w-25 h-25 decorative-shape"
                        style="background: radial-gradient(circle at top right, var(--primary-color) 0%, transparent 70%); opacity: 0.15;">
                    </div>
                    <div class="card-body position-relative z-1 p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">MANAGE
                                </h6>
                                <h2 class="fw-bold kpi-value mb-0">Clinics</h2>
                            </div>
                            <div class="rounded-3 p-2 kpi-icon-container">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 21H21" stroke="var(--primary-color)" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M5 21V7C5 5.89543 5.89543 5 7 5H17C18.1046 5 19 5.89543 19 7V21"
                                        stroke="var(--primary-color)" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M9 10H15" stroke="var(--primary-color)" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M9 14H15" stroke="var(--primary-color)" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                        <div class="border-top pt-3 mt-3 kpi-divider">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-body-secondary p-1 me-2 border kpi-small-icon">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M15 12L12 15L9 12" stroke="var(--primary-color)" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                    <p class="text-muted kpi-footer mb-0">View All Registered</p>
                                </div>
                                <a href="#" class="btn btn-sm btn-outline-primary stretched-link">Go</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Users Card -->
            <div class="col-md-4">
                <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-info"
                    data-bs-theme="light,dark">
                    <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-grid-sa-2" x="0" y="0" width="40" height="40"
                                    patternUnits="userSpaceOnUse">
                                    <rect x="0" y="0" width="2" height="2" fill="var(--primary-color)"
                                        fill-opacity="0.2" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#pattern-grid-sa-2)" />
                        </svg>
                    </div>
                    <div class="position-absolute top-0 end-0 w-25 h-25 decorative-shape"
                        style="background: radial-gradient(circle at top right, var(--primary-color) 0%, transparent 70%); opacity: 0.15;">
                    </div>
                    <div class="card-body position-relative z-1 p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">SYSTEM
                                </h6>
                                <h2 class="fw-bold kpi-value mb-0">Users</h2>
                            </div>
                            <div class="rounded-3 p-2 kpi-icon-container">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M17 20C17 18.3431 15.6569 17 14 17H10C8.34315 17 7 18.3431 7 20M21 20C21 18.3431 19.6569 17 18 17M3 20C3 18.3431 4.34315 17 6 17M12 13C13.6569 13 15 11.6569 15 10C15 8.34315 13.6569 7 12 7C10.3431 7 9 8.34315 9 10C9 11.6569 10.3431 13 12 13ZM19 13C20.6569 13 22 11.6569 22 10C22 8.34315 20.6569 7 19 7C18.6743 7 18.3619 7.0522 18.0706 7.15011M5 13C3.34315 13 2 11.6569 2 10C2 8.34315 3.34315 7 5 7C5.3257 7 5.63813 7.0522 5.92939 7.15011"
                                        stroke="var(--primary-color)" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                        <div class="border-top pt-3 mt-3 kpi-divider">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-body-secondary p-1 me-2 border kpi-small-icon">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 5V19" stroke="var(--primary-color)" stroke-width="1.5"
                                                stroke-linecap="round" />
                                            <path d="M5 12H19" stroke="var(--primary-color)" stroke-width="1.5"
                                                stroke-linecap="round" />
                                        </svg>
                                    </div>
                                    <p class="text-muted kpi-footer mb-0">Manage Roles</p>
                                </div>
                                <a href="#" class="btn btn-sm btn-outline-primary stretched-link">Go</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Card -->
            <div class="col-md-4">
                <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-primary" data-bs-theme="light,dark">
                    <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-circle-sa-3" x="0" y="0" width="40" height="40"
                                    patternUnits="userSpaceOnUse">
                                    <circle cx="20" cy="20" r="2" fill="var(--primary-color)"
                                        fill-opacity="0.2" />
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#pattern-circle-sa-3)" />
                        </svg>
                    </div>
                    <div class="position-absolute top-0 end-0 w-25 h-25 decorative-shape"
                        style="background: radial-gradient(circle at top right, var(--primary-color) 0%, transparent 70%); opacity: 0.15;">
                    </div>
                    <div class="card-body position-relative z-1 p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div>
                                <h6 class="card-title fw-medium mb-1 kpi-label" style="letter-spacing: 0.5px;">GLOBAL
                                </h6>
                                <h2 class="fw-bold kpi-value mb-0">Settings</h2>
                            </div>
                            <div class="rounded-3 p-2 kpi-icon-container">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                                        stroke="var(--primary-color)" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke="var(--primary-color)"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                        <div class="border-top pt-3 mt-3 kpi-divider">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-body-secondary p-1 me-2 border kpi-small-icon">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 8V12L15 15" stroke="var(--primary-color)" stroke-width="1.5"
                                                stroke-linecap="round" />
                                        </svg>
                                    </div>
                                    <p class="text-muted kpi-footer mb-0">System Config</p>
                                </div>
                                <a href="#" class="btn btn-sm btn-outline-primary stretched-link">Go</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
