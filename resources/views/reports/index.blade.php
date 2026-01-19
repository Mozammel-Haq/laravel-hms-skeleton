<x-app-layout>
    @push('styles')
        <style>
            /* Additional color themes for report cards */
            .kpi-danger {
                background: linear-gradient(135deg, #fdf0f0 0%, #fce8e8 100%);
                border-color: #f5c6cb !important;
            }

            .kpi-secondary {
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                border-color: #dee2e6 !important;
            }

            [data-bs-theme="dark"] .kpi-danger {
                background: linear-gradient(135deg, #5c1a1a 0%, #421414 100%) !important;
                border-color: #dc3545 !important;
            }

            [data-bs-theme="dark"] .kpi-secondary {
                background: linear-gradient(135deg, #343a40 0%, #212529 100%) !important;
                border-color: #6c757d !important;
            }

            /* Icon container for report cards */
            .kpi-card .kpi-icon-container {
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 12px;
            }

            /* Add hover effect */
            .kpi-card {
                transition: all 0.3s ease;
                border: 1.5px solid;
            }

            .kpi-card:hover {
                transform: translateY(-4px);
            }

            /* Update CSS variables for danger and secondary colors */
            :root {
                --danger-color: #dc3545;
                --secondary-color: #6c757d;
            }

            [data-bs-theme="dark"] {
                --danger-color: #e6858d;
                --secondary-color: #adb5bd;
            }

            /* Icon container colors for new themes */
            .kpi-danger .kpi-icon-container {
                background: rgba(220, 53, 69, 0.1) !important;
                border: 1px solid rgba(220, 53, 69, 0.2) !important;
            }

            .kpi-secondary .kpi-icon-container {
                background: rgba(108, 117, 125, 0.1) !important;
                border: 1px solid rgba(108, 117, 125, 0.2) !important;
            }

            [data-bs-theme="dark"] .kpi-danger .kpi-icon-container {
                background: rgba(230, 133, 141, 0.1) !important;
                border: 1px solid rgba(230, 133, 141, 0.2) !important;
            }

            [data-bs-theme="dark"] .kpi-secondary .kpi-icon-container {
                background: rgba(173, 181, 189, 0.1) !important;
                border: 1px solid rgba(173, 181, 189, 0.2) !important;
            }
        </style>
    @endpush
    <div class="container-fluid mx-2">
        <div class="card mb-3 mt-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="page-title mb-0">Reports</h3>
                </div>
                <hr>
            </div>
        </div>
        <div class="row mb-5 g-4">
            <!-- Summary Report -->
            <div class="col-md-4">
                <a href="{{ route('reports.summary') }}" class="text-decoration-none">
                    <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-primary"
                        data-bs-theme="light,dark">
                        <!-- Pattern Background -->
                        <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <pattern id="pattern-circle-summary" x="0" y="0" width="40" height="40"
                                        patternUnits="userSpaceOnUse">
                                        <circle cx="20" cy="20" r="2" fill="var(--primary-color)"
                                            fill-opacity="0.2" />
                                    </pattern>
                                </defs>
                                <rect width="100%" height="100%" fill="url(#pattern-circle-summary)" />
                            </svg>
                        </div>

                        <!-- Decorative Shape -->
                        <div class="position-absolute top-0 end-0 w-25 h-25 decorative-shape"
                            style="background: radial-gradient(circle at top right, var(--primary-color) 0%, transparent 70%); opacity: 0.15;">
                        </div>

                        <div class="card-body position-relative z-1 p-4 text-center">
                            <div class="mb-4 kpi-icon-container mx-auto" style="width: 64px; height: 64px;">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 17H15" stroke="var(--primary-color)" stroke-width="1.5"
                                        stroke-linecap="round" />
                                    <path d="M9 13H15" stroke="var(--primary-color)" stroke-width="1.5"
                                        stroke-linecap="round" />
                                    <path d="M9 9H12" stroke="var(--primary-color)" stroke-width="1.5"
                                        stroke-linecap="round" />
                                    <path
                                        d="M5 21H19C20.1046 21 21 20.1046 21 19V5C21 3.89543 20.1046 3 19 3H5C3.89543 3 3 3.89543 3 5V19C3 20.1046 3.89543 21 5 21Z"
                                        stroke="var(--primary-color)" stroke-width="1.5" />
                                </svg>
                            </div>
                            <h5 class="fw-bold mb-2 text-dark">OPD/IPD Summary</h5>
                            <p class="text-muted small mb-0">Overview metrics, admissions, and recent activity.</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Financial Report -->
            <div class="col-md-4">
                <a href="{{ route('reports.financial') }}" class="text-decoration-none">
                    <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-success"
                        data-bs-theme="light,dark">
                        <!-- Pattern Background -->
                        <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <pattern id="pattern-dots-financial" x="0" y="0" width="30" height="30"
                                        patternUnits="userSpaceOnUse">
                                        <rect x="0" y="0" width="2" height="2" fill="var(--success-color)"
                                            fill-opacity="0.3" />
                                    </pattern>
                                </defs>
                                <rect width="100%" height="100%" fill="url(#pattern-dots-financial)" />
                            </svg>
                        </div>

                        <!-- Decorative Shape -->
                        <div class="position-absolute bottom-0 start-0 w-50 h-25 decorative-shape"
                            style="background: radial-gradient(circle at bottom left, var(--success-color) 0%, transparent 70%); opacity: 0.1;">
                        </div>

                        <div class="card-body position-relative z-1 p-4 text-center">
                            <div class="mb-4 kpi-icon-container mx-auto" style="width: 64px; height: 64px;">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M17 20H7C5.89543 20 5 19.1046 5 18V6C5 4.89543 5.89543 4 7 4H12L19 11V18C19 19.1046 18.1046 20 17 20Z"
                                        stroke="var(--success-color)" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M12 4V11H19" stroke="var(--success-color)" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M9 13H15" stroke="var(--success-color)" stroke-width="1.5"
                                        stroke-linecap="round" />
                                    <path d="M9 17H13" stroke="var(--success-color)" stroke-width="1.5"
                                        stroke-linecap="round" />
                                </svg>
                            </div>
                            <h5 class="fw-bold mb-2 text-dark">Financial Report</h5>
                            <p class="text-muted small mb-0">Revenue, paid vs unpaid invoices, and trends.</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Demographics Report -->
            <div class="col-md-4">
                <a href="{{ route('reports.demographics') }}" class="text-decoration-none">
                    <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-info"
                        data-bs-theme="light,dark">
                        <!-- Pattern Background -->
                        <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <pattern id="pattern-lines-demographics" x="0" y="0" width="40"
                                        height="40" patternUnits="userSpaceOnUse">
                                        <line x1="0" y1="40" x2="40" y2="0"
                                            stroke="var(--info-color)" stroke-width="1" stroke-opacity="0.2" />
                                        <line x1="0" y1="0" x2="40" y2="40"
                                            stroke="var(--info-color)" stroke-width="1" stroke-opacity="0.2" />
                                    </pattern>
                                </defs>
                                <rect width="100%" height="100%" fill="url(#pattern-lines-demographics)" />
                            </svg>
                        </div>

                        <!-- Decorative Shape -->
                        <div class="position-absolute top-50 start-50 translate-middle w-75 h-75 rounded-circle decorative-shape"
                            style="background: radial-gradient(circle at center, var(--info-color) 0%, transparent 70%); opacity: 0.08;">
                        </div>

                        <div class="card-body position-relative z-1 p-4 text-center">
                            <div class="mb-4 kpi-icon-container mx-auto" style="width: 64px; height: 64px;">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M16 7C16 9.20914 14.2091 11 12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7Z"
                                        stroke="var(--info-color)" stroke-width="1.5" />
                                    <path d="M12 14C8.13401 14 5 17.134 5 21H19C19 17.134 15.866 14 12 14Z"
                                        stroke="var(--info-color)" stroke-width="1.5" />
                                    <path d="M15 11C16.6569 11 18 9.65685 18 8" stroke="var(--info-color)"
                                        stroke-width="1.5" />
                                    <path d="M9 11C7.34315 11 6 9.65685 6 8" stroke="var(--info-color)"
                                        stroke-width="1.5" />
                                </svg>
                            </div>
                            <h5 class="fw-bold mb-2 text-dark">Patient Demographics</h5>
                            <p class="text-muted small mb-0">Gender distribution, age groups, and registration trends.
                            </p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Doctor Performance -->
            <div class="col-md-4">
                <a href="{{ route('reports.doctor_performance') }}" class="text-decoration-none">
                    <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-warning"
                        data-bs-theme="light,dark">
                        <!-- Pattern Background -->
                        <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <pattern id="pattern-diagonal-doctor" x="0" y="0" width="20" height="20"
                                        patternUnits="userSpaceOnUse">
                                        <path d="M0 20L20 0" stroke="var(--warning-color)" stroke-width="0.5"
                                            stroke-opacity="0.3" />
                                        <path d="M-10 10L10 -10" stroke="var(--warning-color)" stroke-width="0.5"
                                            stroke-opacity="0.3" />
                                        <path d="M10 30L30 10" stroke="var(--warning-color)" stroke-width="0.5"
                                            stroke-opacity="0.3" />
                                    </pattern>
                                </defs>
                                <rect width="100%" height="100%" fill="url(#pattern-diagonal-doctor)" />
                            </svg>
                        </div>

                        <!-- Decorative Shape -->
                        <div class="position-absolute top-0 start-0 w-25 h-25 decorative-shape">
                            <svg width="100%" height="100%" viewBox="0 0 100 100"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M0,0 L100,0 L0,100 Z" fill="var(--warning-color)" fill-opacity="0.1" />
                            </svg>
                        </div>

                        <div class="card-body position-relative z-1 p-4 text-center">
                            <div class="mb-4 kpi-icon-container mx-auto" style="width: 64px; height: 64px;">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M16 7C16 9.20914 14.2091 11 12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7Z"
                                        stroke="var(--warning-color)" stroke-width="1.5" />
                                    <path d="M12 14C8.13401 14 5 17.134 5 21H19C19 17.134 15.866 14 12 14Z"
                                        stroke="var(--warning-color)" stroke-width="1.5" />
                                    <path
                                        d="M16 21V19C16 17.9391 15.5786 16.9217 14.8284 16.1716C14.0783 15.4214 13.0609 15 12 15H8C6.93913 15 5.92172 15.4214 5.17157 16.1716C4.42143 16.9217 4 17.9391 4 19V21"
                                        stroke="var(--warning-color)" stroke-width="1.5" stroke-linecap="round" />
                                    <path d="M10 9H14" stroke="var(--warning-color)" stroke-width="1.5"
                                        stroke-linecap="round" />
                                </svg>
                            </div>
                            <h5 class="fw-bold mb-2 text-dark">Doctor Performance</h5>
                            <p class="text-muted small mb-0">Top performers by consultations and admissions.</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Tax Report -->
            <div class="col-md-4">
                <a href="{{ route('reports.tax') }}" class="text-decoration-none">
                    <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-danger"
                        data-bs-theme="light,dark">
                        <!-- Pattern Background -->
                        <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <pattern id="pattern-circle-tax" x="0" y="0" width="40" height="40"
                                        patternUnits="userSpaceOnUse">
                                        <circle cx="20" cy="20" r="2" fill="var(--danger-color)"
                                            fill-opacity="0.2" />
                                    </pattern>
                                </defs>
                                <rect width="100%" height="100%" fill="url(#pattern-circle-tax)" />
                            </svg>
                        </div>

                        <!-- Decorative Shape -->
                        <div class="position-absolute top-0 end-0 w-25 h-25 decorative-shape"
                            style="background: radial-gradient(circle at top right, var(--danger-color) 0%, transparent 70%); opacity: 0.15;">
                        </div>

                        <div class="card-body position-relative z-1 p-4 text-center">
                            <div class="mb-4 kpi-icon-container mx-auto" style="width: 64px; height: 64px;">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M19 21H5C3.89543 21 3 20.1046 3 19V5C3 3.89543 3.89543 3 5 3H19C20.1046 3 21 3.89543 21 5V19C21 20.1046 20.1046 21 19 21Z"
                                        stroke="var(--danger-color)" stroke-width="1.5" />
                                    <path d="M9 7H15" stroke="var(--danger-color)" stroke-width="1.5"
                                        stroke-linecap="round" />
                                    <path d="M9 11H15" stroke="var(--danger-color)" stroke-width="1.5"
                                        stroke-linecap="round" />
                                    <path d="M9 15H12" stroke="var(--danger-color)" stroke-width="1.5"
                                        stroke-linecap="round" />
                                    <path d="M7 7V21" stroke="var(--danger-color)" stroke-width="1.5"
                                        stroke-linecap="round" />
                                </svg>
                            </div>
                            <h5 class="fw-bold mb-2 text-dark">Tax Report</h5>
                            <p class="text-muted small mb-0">Tax collection summary and detailed breakdown.</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Clinic Comparison (Super Admin Only) -->
            @role('Super Admin')
                <div class="col-md-4">
                    <a href="{{ route('reports.compare') }}" class="text-decoration-none">
                        <div class="position-relative overflow-hidden rounded-4 h-100 kpi-card kpi-secondary"
                            data-bs-theme="light,dark">
                            <!-- Pattern Background -->
                            <div class="position-absolute top-0 end-0 w-100 h-100 opacity-25 pattern-bg">
                                <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                                    <defs>
                                        <pattern id="pattern-dots-comparison" x="0" y="0" width="30" height="30"
                                            patternUnits="userSpaceOnUse">
                                            <rect x="0" y="0" width="2" height="2" fill="var(--secondary-color)"
                                                fill-opacity="0.3" />
                                        </pattern>
                                    </defs>
                                    <rect width="100%" height="100%" fill="url(#pattern-dots-comparison)" />
                                </svg>
                            </div>

                            <!-- Decorative Shape -->
                            <div class="position-absolute bottom-0 start-0 w-50 h-25 decorative-shape"
                                style="background: radial-gradient(circle at bottom left, var(--secondary-color) 0%, transparent 70%); opacity: 0.1;">
                            </div>

                            <div class="card-body position-relative z-1 p-4 text-center">
                                <div class="mb-4 kpi-icon-container mx-auto" style="width: 64px; height: 64px;">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8 20L12 16M12 16L16 20M12 16V12M12 12L8 8M12 12L16 8"
                                            stroke="var(--secondary-color)" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
                                            stroke="var(--secondary-color)" stroke-width="1.5" />
                                    </svg>
                                </div>
                                <h5 class="fw-bold mb-2 text-dark">Clinic Comparison</h5>
                                <p class="text-muted small mb-0">Compare revenue and operational volume across clinics.</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endrole
        </div>

    </div>
</x-app-layout>
