<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>CityCare - Medical & Hospital Service</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Mozammel Haq">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets') }}/img/favicon.png">

    <!-- Apple Icon -->
    <link rel="apple-touch-icon" href="{{ asset('assets') }}/img/apple-icon.png">

    <!-- Theme Config Js -->
    <script src="{{ asset('assets') }}/js/theme-script.js" type="text/javascript"></script>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/css/bootstrap.min.css">

    <!-- Datetimepicker CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/css/bootstrap-datetimepicker.min.css">

    <!-- Daterangepikcer CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/daterangepicker/daterangepicker.css">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/fontawesome.min.css"
        integrity="sha512-M5Kq4YVQrjg5c2wsZSn27Dkfm/2ALfxmun0vUE3mPiJyK53hQBHYCVAtvMYEC7ZXmYLg8DVG4tF8gD27WmDbsg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Tabler Icon CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/tabler-icons/tabler-icons.min.css">
    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/select2/css/select2.min.css">
    <!-- Simplebar CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/plugins/simplebar/simplebar.min.css">
    <!-- Datatable CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/css/dataTables.bootstrap5.min.css">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('assets') }}/css/style.css" id="app-style">
    <style>
        /* CSS Variables for theming */
        :root {
            /* Light mode colors */
            --kpi-bg-primary: linear-gradient(135deg, #f0f7ff 0%, #e6f0ff 100%);
            --kpi-bg-info: linear-gradient(135deg, #f0f9fb 0%, #e6f4f8 100%);
            --kpi-bg-success: linear-gradient(135deg, #f0f9f5 0%, #e6f4ec 100%);
            --kpi-bg-warning: linear-gradient(135deg, #fff9f0 0%, #fff4e6 100%);

            --kpi-border-primary: #c2d9ff;
            --kpi-border-info: #b6e7f2;
            --kpi-border-success: #b8e6cf;
            --kpi-border-warning: #ffe5b6;

            --primary-color: #0d6efd;
            --info-color: #17a2b8;
            --success-color: #198754;
            --warning-color: #ffc107;
        }

        [data-bs-theme="dark"] {
            /* Dark mode colors */
            --kpi-bg-primary: linear-gradient(135deg, #0d2b5c 0%, #0a1f42 100%);
            --kpi-bg-info: linear-gradient(135deg, #0a3d4c 0%, #072a35 100%);
            --kpi-bg-success: linear-gradient(135deg, #0d4229 0%, #0a2e1d 100%);
            --kpi-bg-warning: linear-gradient(135deg, #664d03 0%, #4d3a02 100%);

            --kpi-border-primary: #1e4b9e;
            --kpi-border-info: #166d84;
            --kpi-border-success: #157347;
            --kpi-border-warning: #996c00;

            --primary-color: #6ea8fe;
            --info-color: #6edff6;
            --success-color: #75b798;
            --warning-color: #ffda6a;
        }

        /* KPI Card Styles */
        .kpi-card {
            border: 1.5px solid;
            transition: all 0.3s ease;
        }

        /* Card specific backgrounds */
        .kpi-primary {
            background: var(--kpi-bg-primary);
            border-color: var(--kpi-border-primary) !important;
        }

        .kpi-info {
            background: var(--kpi-bg-info);
            border-color: var(--kpi-border-info) !important;
        }

        .kpi-success {
            background: var(--kpi-bg-success);
            border-color: var(--kpi-border-success) !important;
        }

        .kpi-warning {
            background: var(--kpi-bg-warning);
            border-color: var(--kpi-border-warning) !important;
        }

        .kpi-danger {
            background: linear-gradient(135deg, #fff5f5 0%, #ffe6e6 100%);
            border-color: #ffc9c9 !important;
        }

        [data-bs-theme="dark"] .kpi-danger {
            background: linear-gradient(135deg, #5c0d0d 0%, #420a0a 100%);
            border-color: #9e1e1e !important;
        }

        /* Icon Container Colors */
        .kpi-primary .kpi-icon-container {
            background: rgba(var(--bs-primary-rgb), 0.1) !important;
            border: 1px solid rgba(var(--bs-primary-rgb), 0.2) !important;
            color: var(--primary-color) !important;
        }

        .kpi-info .kpi-icon-container {
            background: rgba(var(--bs-info-rgb), 0.1) !important;
            border: 1px solid rgba(var(--bs-info-rgb), 0.2) !important;
            color: var(--info-color) !important;
        }

        .kpi-success .kpi-icon-container {
            background: rgba(var(--bs-success-rgb), 0.1) !important;
            border: 1px solid rgba(var(--bs-success-rgb), 0.2) !important;
            color: var(--success-color) !important;
        }

        .kpi-warning .kpi-icon-container {
            background: rgba(var(--bs-warning-rgb), 0.1) !important;
            border: 1px solid rgba(var(--bs-warning-rgb), 0.2) !important;
            color: var(--warning-color) !important;
        }

        .kpi-danger .kpi-icon-container {
            background: rgba(var(--bs-danger-rgb), 0.1) !important;
            border: 1px solid rgba(var(--bs-danger-rgb), 0.2) !important;
            color: var(--bs-danger) !important;
        }

        /* SVG Pattern Fills */
        .kpi-primary pattern rect {
            fill: var(--primary-color);
        }

        .kpi-info pattern rect {
            fill: var(--info-color);
        }

        .kpi-success pattern rect {
            fill: var(--success-color);
        }

        .kpi-warning pattern rect {
            fill: var(--warning-color);
        }

        .kpi-danger pattern rect {
            fill: var(--bs-danger);
        }

        /* Decorative Shape Gradients */
        .kpi-primary .decorative-shape {
            background: radial-gradient(circle at top right, var(--primary-color) 0%, transparent 70%);
        }

        .kpi-info .decorative-shape {
            background: radial-gradient(circle at top right, var(--info-color) 0%, transparent 70%);
        }

        .kpi-success .decorative-shape {
            background: radial-gradient(circle at top right, var(--success-color) 0%, transparent 70%);
        }

        .kpi-warning .decorative-shape {
            background: radial-gradient(circle at top right, var(--warning-color) 0%, transparent 70%);
        }

        .kpi-danger .decorative-shape {
            background: radial-gradient(circle at top right, var(--bs-danger) 0%, transparent 70%);
        }

        /* Text Colors */
        .kpi-primary .kpi-label {
            color: var(--primary-color) !important;
            opacity: 0.8;
        }

        .kpi-info .kpi-label {
            color: var(--info-color) !important;
            opacity: 0.8;
        }

        .kpi-success .kpi-label {
            color: var(--success-color) !important;
            opacity: 0.8;
        }

        .kpi-warning .kpi-label {
            color: var(--warning-color) !important;
            opacity: 0.8;
        }

        .kpi-danger .kpi-label {
            color: var(--bs-danger) !important;
            opacity: 0.8;
        }

        /* Pattern opacity adjustment for dark mode */
        [data-bs-theme="dark"] .pattern-bg {
            opacity: 0.15 !important;
        }

        [data-bs-theme="dark"] .decorative-shape {
            opacity: 0.1 !important;
        }

        /* KPI Label Base */
        .kpi-label {
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* KPI Value Base */
        .kpi-value {
            color: var(--bs-body-color) !important;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.05);
        }

        [data-bs-theme="dark"] .kpi-value {
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        /* Icon Container Base */
        .kpi-icon-container {
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
        }

        /* Small Icon Base */
        .kpi-small-icon {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        border-color: rgba(var(--bs-primary-rgb), 0.3) !important;
        background-color: var(--bs-body-bg) !important;
        }

        .kpi-card:nth-child(2) .kpi-small-icon {
            border-color: rgba(var(--bs-info-rgb), 0.3) !important;
        }

        .kpi-card:nth-child(3) .kpi-small-icon {
            border-color: rgba(var(--bs-success-rgb), 0.3) !important;
        }

        .kpi-card:nth-child(4) .kpi-small-icon {
            border-color: rgba(var(--bs-warning-rgb), 0.3) !important;
        }

        /* Divider */
        .kpi-divider {
            border-color: rgba(var(--bs-primary-rgb), 0.2) !important;
        }

        .kpi-card:nth-child(2) .kpi-divider {
            border-color: rgba(var(--bs-info-rgb), 0.2) !important;
        }

        .kpi-card:nth-child(3) .kpi-divider {
            border-color: rgba(var(--bs-success-rgb), 0.2) !important;
        }

        .kpi-card:nth-child(4) .kpi-divider {
            border-color: rgba(var(--bs-warning-rgb), 0.2) !important;
        }

        /* Footer Text */
        .kpi-footer {
            color: var(--bs-secondary-color) !important;
            margin-bottom: 0;
        }
    </style>
    @stack('styles')
</head>

<body>

    <main class="main-wrapper">

        <x-layout.header />
        <x-layout.sidebar />
        <div class="page-wrapper">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show m-4" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('warning'))
                <div class="alert alert-warning alert-dismissible fade show m-4" role="alert">
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{ $slot }}
            <x-layout.footer />

        </div>
    </main>

    <!-- jQuery (MUST be first) -->
    <script src="{{ asset('assets') }}/js/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="{{ asset('assets') }}/js/bootstrap.bundle.min.js"></script>

    <!-- Select2 JS (after jQuery!) -->
    <script src="{{ asset('assets') }}/plugins/select2/js/select2.min.js"></script>
    <!-- Other plugins -->
    <script src="{{ asset('assets') }}/plugins/simplebar/simplebar.min.js"></script>
    <script src="{{ asset('assets') }}/plugins/apexchart/apexcharts.min.js"></script>
    <script src="{{ asset('assets') }}/plugins/apexchart/chart-data.js"></script>
    <script src="{{ asset('assets') }}/js/moment.min.js"></script>
    <script src="{{ asset('assets') }}/plugins/daterangepicker/daterangepicker.js"></script>
    <script src="{{ asset('assets') }}/js/bootstrap-datetimepicker.min.js"></script>
    <!-- Datatable JS -->
    <script src="{{ asset('assets') }}/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('assets') }}/js/dataTables.bootstrap5.min.js"></script>
    <!-- Your custom scripts -->
    @stack('scripts')

    <script src="{{ asset('assets') }}/js/script.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="{{ asset('assets') }}/js/rocket-loader.min.js" defer></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize column filters for all tables
            const tables = document.querySelectorAll('table');
            tables.forEach((table, index) => {
                // Skip if table has no header
                if (!table.querySelector('thead')) return;

                // Assign unique ID if missing
                if (!table.id) {
                    table.id = 'data-table-' + index;
                }

                // Create Dropdown Container
                const dropdown = document.createElement('div');
                dropdown.className = 'dropdown d-flex justify-content-end mb-2';

                // Dropdown Toggle Button
                const btn = document.createElement('button');
                btn.className = 'btn btn-sm btn-primary dropdown-toggle d-flex align-items-center gap-1 mt-1';
                btn.type = 'button';
                btn.setAttribute('data-bs-toggle', 'dropdown');
                btn.setAttribute('aria-expanded', 'false');
                btn.setAttribute('data-bs-auto-close', 'outside'); // Keep open on click
                btn.innerHTML = '<i class="ti ti-columns bg-transparent text-white"></i> Columns';

                // Dropdown Menu
                const menu = document.createElement('ul');
                menu.className = 'dropdown-menu dropdown-menu-end p-3 shadow';
                menu.style.minWidth = '250px';
                menu.style.maxHeight = '300px';
                menu.style.overflowY = 'auto';

                // Add Header to Menu
                const header = document.createElement('li');
                header.innerHTML = '<h6 class="dropdown-header text-uppercase text-muted p-0 mb-2">Visible Columns</h6>';
                menu.appendChild(header);

                // Add Reset Option
                const resetItem = document.createElement('li');
                resetItem.className = 'mb-2 pb-2 border-bottom';
                resetItem.innerHTML = '<a href="#" class="dropdown-item p-0 text-primary small">Reset to Default</a>';
                resetItem.querySelector('a').onclick = (e) => {
                    e.preventDefault();
                    resetColumns(table, menu);
                };
                menu.appendChild(resetItem);

                // Populate Menu Items
                const headers = table.querySelectorAll('thead tr:first-child th');
                headers.forEach((th, colIndex) => {
                    const text = th.textContent.trim();
                    // Skip empty headers or actions column usually at the end
                    if (!text && colIndex !== headers.length - 1) return;

                    const li = document.createElement('li');
                    li.className = 'ms-5 form-check form-switch mb-2 d-flex align-items-center gap-2';

                    const input = document.createElement('input');
                    input.className = 'form-check-input mt-0';
                    input.type = 'checkbox';
                    input.role = 'switch';
                    input.id = `col-toggle-${table.id}-${colIndex}`;
                    input.checked = th.style.display !== 'none';
                    input.onchange = () => toggleColumn(table, colIndex, input.checked);

                    const label = document.createElement('label');
                    label.className = 'form-check-label text-truncate cursor-pointer';
                    label.htmlFor = `col-toggle-${table.id}-${colIndex}`;
                    label.textContent = text || `Column ${colIndex + 1}`;
                    label.style.cursor = 'pointer';
                    label.style.maxWidth = '180px';

                    li.appendChild(input);
                    li.appendChild(label);
                    menu.appendChild(li);
                });

                dropdown.appendChild(btn);
                dropdown.appendChild(menu);

                // Insert dropdown before the table's container (if responsive) or the table itself
                const responsiveParent = table.closest('.table-responsive') || table.closest('.table-wrapper');
                if (responsiveParent) {
                    responsiveParent.parentNode.insertBefore(dropdown, responsiveParent);
                } else {
                    table.parentNode.insertBefore(dropdown, table);
                }
            });

            function toggleColumn(table, index, show) {
                // Toggle header
                const th = table.querySelector(`thead tr th:nth-child(${index + 1})`);
                if (th) th.style.display = show ? '' : 'none';

                // Toggle all rows
                const rows = table.querySelectorAll('tbody tr');
                rows.forEach(row => {
                    const td = row.querySelector(`td:nth-child(${index + 1})`);
                    if (td) td.style.display = show ? '' : 'none';
                });
            }

            function resetColumns(table, menu) {
                const inputs = menu.querySelectorAll('input[type="checkbox"]');
                inputs.forEach(input => {
                    input.checked = true;
                    // Trigger change event or manually call toggle
                    const colIndex = parseInt(input.id.split('-').pop());
                    toggleColumn(table, colIndex, true);
                });
            }
        });
    </script>

</body>

</html>
