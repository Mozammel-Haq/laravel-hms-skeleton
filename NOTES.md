## AI Context Notes – HRM, Attendance, Holidays, Appointments

### 1. High-Level Goals

- Build a realistic HRM module (training, profiles, attendance, leaves, holidays, shifts) that stays in sync with clinical workflows.
- Ensure appointments respect HR holidays so clinics cannot be booked on full-day holidays.
- Make attendance, timesheets, and overtime behave like a single coherent system instead of isolated features.
- Improve the HRM dashboards (Vue) for better UX and dark mode compatibility.

### 2. Core Design Principles

- **Tenant isolation**
  - All HRM-related models use `BaseTenantModel` and `clinic_id` to scope data.
  - API v2 routes (`/api/v2/*`) are protected by `auth:sanctum` and `api.tenant`.
  - Controllers always filter by `clinic_id` matching the authenticated staff user.

- **Permission and role gating**
  - Reuse existing abilities such as `view_staff`, `manage_leaves`, `create_staff`, etc.
  - HRM Vue dashboards compute abilities from `auth.user.abilities` and show/hide menus accordingly.

- **Department separation**
  - Clinical department: `Doctor.primary_department_id` used for medical workflows.
  - HR department: `User.department_id` used for HR-related grouping and reporting.
  - Avoid mixing the two to keep clinical and HR concerns independent.

- **Holiday-aware scheduling**
  - HR holidays (`HrmHoliday`) must affect both:
    - Appointment availability and booking.
    - Attendance and downstream HRM records (timesheets, overtime).

- **Single source of truth for working time**
  - Attendance is the base record for presence and `worked_hours`.
  - Timesheets break down worked hours by project/task.
  - Overtime is additional paid/extra hours on top of normal working hours.
  - Validation ties these pieces together so they cannot contradict each other.

- **Frontends remain operational on holidays**
  - Dashboards still load on holidays.
  - Appointment booking interfaces appear, but no slots are available and booking requests for full-day holidays are blocked.

- **Theming and dark mode**
  - Vue dashboards use Bootstrap CSS variables (`var(--bs-body-bg)`, `var(--bs-border-color)`, etc.) rather than hardcoded colors.
  - Ensures components like Shift Calendar automatically adapt to light/dark themes.

### 3. Implemented Changes – Backend

#### 3.1 Appointment slot engine now respects HR holidays

**File:** `app/Services/AppointmentService.php`

- Method `getAvailableSlots(Doctor $doctor, string $date, ?int $clinic_id = null): array`:
  - Determines which `clinic_id` to use:
    - Prefer explicit `$clinic_id` passed in.
    - Fallback to `doctor->primaryDepartment->clinic_id` when available.
  - Before fetching schedules and generating slots:
    - Looks up an active full-day `HrmHoliday` for the resolved `clinic_id` and date.
    - If found, returns an empty array (no slots for that date).
  - All slot consumers (Blade controllers and REST APIs) automatically inherit this behavior.

#### 3.2 Staff booking (reception) blocked on full-day holidays

**File:** `app/Http/Controllers/AppointmentBookingController.php`

- In `store(Request $request)`:
  - Validates basic appointment fields (doctor, patient, clinic, date, time).
  - Normalizes `appointment_date`, `start_time`, and `end_time`.
  - Resolves clinic context via `TenantContext`:
    - If `TenantContext::hasClinic()`, use that clinic.
    - Otherwise, use the `clinic_id` from the form.
  - Checks `HrmHoliday` for the target clinic/date with:
    - `status = active`
    - `is_full_day = true`
  - If a holiday exists:
    - Returns back with a validation error on `appointment_date`.
    - Leaves the rest of the booking workflow unchanged (slot conflict checks, fee calculation, notifications).

#### 3.3 Patient API: slots and booking blocked on full-day holidays

**File:** `app/Http/Controllers/Api/AppointmentsApiController.php`

- Constructor uses `AppointmentService` as before.

- `slots(Request $request)` (patient API to fetch available slots):
  - Accepts `doctor_id`, `date`, and optional `clinic_id`.
  - Resolves `clinicId` from query or `X-Clinic-ID` header.
  - Before calling `AppointmentService`:
    - Checks for an active full-day `HrmHoliday` on that clinic/date.
    - If found, returns `['slots' => []]` (no booking options).
  - Otherwise:
    - Calls `getAvailableSlots`.
    - Filters out booked slots and maps to frontend format.

- `store(Request $request)` (patient API to create an appointment):
  - Validates `doctor_id`, `patient_id`, `department_id`, `appointment_date`, `start_time`, `end_time`, optional `clinic_id`, `appointment_type`, etc.
  - Resolves `clinicId` from request or `X-Clinic-ID`.
  - Normalizes `bookingDate` via `Carbon`.
  - Before computing end time and conflict checks:
    - Computes `holidayClinicId` (prefer explicit clinic, else patient clinic).
    - Checks `HrmHoliday` for full-day active holiday on `bookingDate`.
    - If present, returns `422` JSON: `"Cannot book appointments on a full-day clinic holiday."`
  - If no holiday:
    - Calls `getAvailableSlots` to compute a canonical end time for the chosen start time.
    - Applies conflict rules:
      - Prevent multiple active appointments on the same day for a patient.
      - Scope by clinic if available.
    - Stores appointment with corrected `appointment_type` mapping:
      - Frontend sends `new` / `follow_up`.
      - DB expects `'in_person'` / `'online'`.
      - Non-standard values are normalized to `'in_person'` and appended into `reason_for_visit`.

#### 3.4 Attendance: holiday-aware presence and hours

**File:** `app/Http/Controllers/Api/V2/HrmAttendanceController.php`

- Dependencies:
  - `HrmAttendance` (attendance records).
  - `HrmHoliday` (holiday definitions).
  - `User` (staff).

- `store(Request $request)`:
  - Enforces:
    - Actor must have `view_staff`.
    - `user_id` must belong to the same `clinic_id` as the actor.
    - Only one `HrmAttendance` per `user_id` + `attendance_date` + clinic.
  - Holiday logic:
    - Loads a full-day active `HrmHoliday` for `actor->clinic_id` + `attendance_date`.
    - Determines `status = $validated['status'] ?? 'present'`.
    - If holiday and `status === 'present'`:
      - Returns `422` JSON with guidance to use holiday/leave status instead.
  - Worked hours:
    - If both `check_in_time` and `check_out_time` are provided:
      - Normalizes times, allowing overnight shifts (out <= in adds a day).
      - Computes `worked_hours` in hours.
      - If holiday exists:
        - Returns `422` stating working hours cannot be recorded on a full-day holiday.

- `update(Request $request, HrmAttendance $attendance)`:
  - Enforces clinic ownership and `view_staff`.
  - Validates optional `check_in_time`, `check_out_time`, `status`, `is_late`, `is_early_exit`.
  - Holiday logic:
    - Loads any full-day active holiday for the attendance’s `attendance_date`.
    - Computes target status from new data or existing record.
    - If holiday and target status `present`:
      - Blocks the update (`422`) with the same guidance.
  - Worked hours:
    - Recalculates `worked_hours` from new effective check-in/out.
    - If holiday is active:
      - Blocks the change (`422`), preventing hours on a holiday.

#### 3.5 Timesheets: tied to attendance worked hours

**File:** `app/Http/Controllers/Api/V2/HrmTimesheetController.php`

- Dependencies:
  - `HrmTimesheet` (project/task breakdown).
  - `HrmAttendance` (source of truth for worked hours).
  - `User`.

- `index(Request $request)`:
  - Filters timesheets by clinic, user_id, date range, and status.
  - Restricts non-HR users to their own records.

- `store(Request $request)`:
  - Validates `user_id`, `date`, `hours`, optional `project`, `task`, `notes`, `status`.
  - Ensures:
    - Target user belongs to actor’s clinic.
    - There is an `HrmAttendance` record for that user and date in the same clinic.
  - Sums existing timesheet hours for the user/date/clinic.
  - Computes `newTotalHours = existingHours + incoming hours`.
  - Looks up `attendance->worked_hours`:
    - If `worked_hours <= 0`:
      - Blocks timesheet creation.
    - If `newTotalHours > worked_hours`:
      - Blocks with message: timesheets cannot exceed worked hours.

- `update(Request $request, HrmTimesheet $timesheet)`:
  - Enforces clinic ownership and `view_staff`.
  - On updating `hours`:
    - Fetches the matching attendance record.
    - Re-sums hours from other timesheets on that date.
    - Computes `newTotalHours` including the proposed value.
    - Uses `attendance->worked_hours` as upper bound.
    - Blocks updates that exceed worked hours or where worked hours are zero.

#### 3.6 Overtime: requires attendance and reasonable bounds

**File:** `app/Http/Controllers/Api/V2/HrmOvertimeController.php`

- Dependencies:
  - `HrmOvertime` (overtime entries).
  - `HrmAttendance` (worked hours).
  - `User`.

- `index(Request $request)`:
  - Lists overtime entries by clinic, user, date range, and status.
  - Non-HR users see their own records only.

- `store(Request $request)`:
  - Validates `user_id`, `date`, `hours`, `multiplier`, `reason`, `status`.
  - Ensures:
    - Target user belongs to actor’s clinic.
    - Attendance exists for that date.
    - `attendance->worked_hours > 0`.
  - Sums existing overtime hours for that date.
  - Computes `newTotalOvertime`.
  - Enforces an upper bound:
    - Total overtime hours per day cannot exceed 24 (conservative guardrail).

- `update(Request $request, HrmOvertime $overtime)`:
  - Enforces clinic ownership and `view_staff`.
  - On changing `hours`:
    - Ensures attendance exists and worked hours are > 0.
    - Sums other overtime records for the same date.
    - Ensures new total overtime hours do not exceed 24.

### 4. Implemented Changes – Frontend (HRM Vue)

#### 4.1 Shift Calendar dark mode optimization

**File:** `dashboards/hrm-vue/src/views/ShiftCalendar.vue`

- Uses Bootstrap CSS variables instead of hardcoded colors:
  - `var(--bs-body-bg)` for background.
  - `var(--bs-border-color)` for borders.
  - `var(--bs-secondary-color)` for header text.
  - `var(--bs-primary)` and RGBA for “today” highlight.
- Adds subtle hover effects (background, box-shadow, slight translateY).
- Reduces opacity for other-month dates instead of forcing a light background.
- Result: Shift calendar cards look consistent in both light and dark themes, matching the `LeaveCalendar` style.

### 5. Earlier HRM Context (High-Level)

- Training module:
  - New HRM Training module implemented using `BaseTenantModel`.
  - Follows the same API v2 patterns (Sanctum + `api.tenant`).
  - Vue routes guarded with `canView*` permissions, matching other HRM sections.

- Profiles UX:
  - `Profiles.vue` updated to fix edit behavior.
  - Uses a Bootstrap modal for editing instead of an awkward inline form.

- Department semantics:
  - Clarified that `Doctor.primary_department_id` drives clinical workflows.
  - `User.department_id` is used for HR reporting and filters.

### 6. Testing and Status

- Backend tests:
  - `composer test` was run after introducing these changes.
  - Result: 5 failing tests, 107 passing, 450 assertions.
  - Failures are unrelated to the HRM/holiday changes (examples: redirect vs 200 on homepage, invalid JSON from a lab search route, missing `patients` table in a SQLite connection for a specific test).
  - No new failures clearly pointing to the attendance/holiday logic.

- Static analysis:
  - IDE diagnostics show no syntax or type errors in the modified controllers and service.

### 7. How to Use This Document in Future AI Sessions

- Use this file as a quick context seed when starting a new AI-assisted session on this project.
- It summarizes:
  - The invariants the HRM and appointment systems must respect.
  - Which files are central to holidays, attendance, timesheets, overtime, and shift calendar UI.
  - The rationale behind key validations and constraints.
- When extending HRM or appointment features:
  - Keep holiday-awareness consistent (check `HrmHoliday` for full-day holidays).
  - Preserve tenant isolation and ability checks.
  - Respect attendance as the source of truth for worked hours.

