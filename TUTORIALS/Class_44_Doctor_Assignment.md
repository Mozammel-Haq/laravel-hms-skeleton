# Class 44: Doctor Assignment

## Introduction
Sometimes, a doctor works in multiple departments or even multiple clinics.
Currently, our `Doctor` model has `department_id` and `clinic_id`. This is a "Primary Assignment".

If we wanted to support multi-department assignment, we would need a pivot table `doctor_department`.

## 1. Refactoring (Conceptual)
For this tutorial, we will stick to **One Doctor = One Department** to keep complexity manageable. However, if you are building for a large enterprise hospital:
1.  Create `department_doctor` table.
2.  Move `schedules` to be linked to `department_id` as well (e.g., "Monday Morning in Cardiology", "Monday Afternoon in Neurology").

## 2. Validation
Ensure that when assigning a doctor to a department, the department belongs to the same clinic.

```php
if ($department->clinic_id !== $doctor->clinic_id) {
    throw new Exception("Security Violation");
}
```

## Summary
We acknowledge the complexity of real-world assignments but choose a pragmatic path for this course.
