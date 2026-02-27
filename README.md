# Laravel HMS Skeleton

A complete hospital/clinic management system built on Laravel 12 and designed for multi‑clinic, role‑based deployments.

This repository combines a production‑grade Laravel backend, a React/Vite frontend (optional) and dozens of modules commonly required by healthcare providers. It ships with a rich set of features, comprehensive automated tests, and utilities that make it easy to extend for your own hospital information system (HIS) or electronic medical record (EMR).

---

## 🚀 Key Features

### Multi‑Tenancy & Clinics
- **Clinic/tenant context** driven by `TenantContext` and a `clinic_id` global scope.
- Super‑admin can switch between clinics and view global statistics.
- Clinic‑level isolation ensures data (patients, appointments, bills, etc.) is scoped correctly.

### Role‑Based Access Control
- Powered by [spatie/laravel-permission](https://github.com/spatie/laravel-permission).
- Predefined roles: Super Admin, Clinic Admin, Doctor, Receptionist, Pharmacist, Lab Tech, Accountant, Nurse, etc.
- Permissions managed via a UI (see **Admin / Roles & Permissions** section).
- Policies and middleware guard every resource.

### Dashboards
- Dynamic dashboards per role with charts and stats (patients, doctors, revenue, appointments, popular departments, etc.).
- Super Admin dashboard shows system growth, clinic counts, user roles distribution, and lets you select a clinic context.

### Patients & Medical History
- Full CRUD for patients with global search/duplicate checking.
- Patient linking across clinics.
- Embedded medical history (conditions, allergies, surgeries, immunizations).
- Downloadable prescriptions, lab results, medical history, and invoices for patients.

### Appointments & Scheduling
- Standard appointment CRUD plus requests and status updates.
- Doctor slot APIs, booking interface, and new appointment booking screen.
- Doctor schedule exceptions, requests, calendar view, and self‑management endpoints.

### Clinical Module
- Consultations tied to appointments; prescription creation from consultation.
- Prescription management with printing and patient access.
- Vitals recording, nursing notes, and doctor profile extras (education, awards, certifications).

### Pharmacy & Inventory
- POS interface for over‑the‑counter (OTC) sales.
- Medicine catalog, batch inventory, and search API.
- Pharmacy‑specific routes hidden from non‑pharmacy users.

### Laboratory
- Lab order creation, results entry, download/view of results files.
- Lab test catalog management.
- Invoice generation from lab orders.

### In‑Patient Department (IPD)
- Admission workflow, ward/room/bed management.
- Bed assignments, service billing, rounds, discharges, and bed status tracking.

### Billing & Payments
- Invoice creation, viewing, deletion/restore.
- Support for payments (cash, digital, Stripe online payments).
- Transaction listing and patient‑pending items API.
- Stripe webhook handler.

### Reports
- Financial, demographics, summary, doctor performance, tax, pharmacy profit, and custom comparisons.

### Administration & Settings
- Clinic management (including status, profile, image uploads).
- Doctor/staff CRUD with assignment interfaces and schedule helpers.
- System clinic switching and clearing context for super admins and doctors.
- Activity logs and notification management.
- Roles/permissions and super‑admin/clinic‑admin user management UIs.

### Utilities
- Global patient search API, link, and existence check.
- Static resource download routes for patients (prescription, lab results, invoices).
- Middleware `EnsureClinicContext` ensures users operate within a clinic.
- Comprehensive seeders (`StandardDataSeeder`, `UserSeeder`) for sample clinics, departments, wards, medicines, lab tests, and default users.

### Frontend & Assets
- Optional React/Vite front-end lives in `HMS-FrontEndWithReact` directory; not required for backend operation.
- Tailwind CSS configuration is included.

### Testing
- Over 60 Pest feature/Unit tests exercise everything from auth flows to billing logic, appointment booking, multi‑clinic behaviors, IPD flows, pharmacy, lab, reports, and RBAC.

---

## 🛠️ Tech Stack

- PHP 8.2
- Laravel 12
- MySQL / PostgreSQL / SQLite (tested) via Eloquent
- Laravel Sanctum for API authentication
- Spatie Laravel Permission for RBAC
- Doctrine DBAL for migrations and introspection
- Stripe PHP SDK for online payments
- Maatwebsite Excel for exports
- Pest PHP for testing
- Breeze for authentication scaffolding

---

## 📂 Repository Structure

```
app/                # Laravel application code (controllers, models, services)
bootstrap/          # framework bootstrap files
config/             # configuration files
database/           # migrations, seeders, factories
public/             # web server document root (assets, index.php)
resources/          # Blade templates, JS, CSS
routes/             # route definitions (web.php, api.php)
storage/            # compiled views, logs, caches
tests/              # Pest and PHPUnit tests
HMS-FrontEndWithReact/ # optional React front‑end
```

---

## ✅ Getting Started (Development)

### Requirements

- PHP ^8.2
- Composer
- Node.js & npm/yarn
- MySQL, PostgreSQL or SQLite

### Installation

```bash
# clone repository
git clone https://github.com/Mozammel-Haq/laravel-hms-skeleton.git
cd laravel-hms-skeleton

# install PHP dependencies
composer install

# copy env file and generate key
cp .env.example .env
php artisan key:generate

# configure database in .env (DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE...)
# example for sqlite:
# touch database/database.sqlite
# DB_CONNECTION=sqlite
# DB_DATABASE=${PWD}/database/database.sqlite

# run migrations & seed sample data
php artisan migrate --force
php artisan db:seed --class=StandardDataSeeder
php artisan db:seed --class=UserSeeder

# install frontend dependencies (optional)
npm install
npm run dev   # for development
npm run build # for production

# start the application
php artisan serve

# run tests
php artisan test
# or with coverage
npm run test
```

### Useful Scripts

The `composer.json` defines convenient scripts:

- `composer setup` – install dependencies, copy env, migrate & build front‑end.
- `composer dev` – serve Laravel, run queue listener, and start Vite concurrently.
- `composer test` – clear config cache and run Pest tests.

---

## ⚙️ Configuration

Most configuration is standard Laravel. Key environment variables:

```
APP_NAME="HMS Skeleton"
APP_ENV=local
APP_KEY=base64:...
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hms
DB_USERNAME=root
DB_PASSWORD=

STRIPE_SECRET=sk_live_...
STRIPE_WEBHOOK_SECRET=whsec_...

MAIL_MAILER=smtp
MAIL_HOST=...
MAIL_PORT=...
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_ENCRYPTION=tls
```

### Multi‑Clinic Context

Super‑admins may switch context by hitting `/system/switch-clinic/{clinic}`; doctors have a dedicated route for the same. Context is stored in session and enforced by `EnsureClinicContext` middleware. Refer to `app/Support/TenantContext.php` for details.

---

## 🔐 Roles & Permissions

The system uses a modular permission naming convention (`action_entity`, e.g. `view_patients`, `create_invoices`).

- Manage roles and permissions under **Admin → Roles & Permissions** (available only to Super Admins).
- Assign roles to staff from the **Staff** management screens.
- Permissions fit naturally into controllers and routes via `can:` middleware.

---

## 🧪 Testing

Tests are written with [Pest](https://pestphp.com) and cover nearly every feature:

```bash
php artisan test           # run all tests
php artisan test --filter=AppointmentFlowTest
```

Test data is seeded dynamically; many feature tests manually manipulate the `TenantContext` to simulate multi‑clinic scenarios.

---

## 🤝 Contributing

Contributions are welcome! Please follow standard Laravel convention:

1. Fork the repository.
2. Create a feature branch (`git checkout -b feature/foo`).
3. Make your changes and add tests.
4. Submit a pull request with a clear description.

Ensure your code passes `php artisan test` and follows [PSR‑12](https://www.php-fig.org/psr/psr-12/) coding style (run `composer run-script lint` if configured).

---

## 📄 License

This project is open-source and licensed under the **MIT License**.

---

> ⭐ Built by **Mozammel-Haq** as a scalable starting point for health‑care applications. Feel free to adapt and extend!
