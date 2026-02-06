# Class 06: The Multi-Tenancy Concept

## Introduction
Before we write more code, we must understand the architectural pattern we are implementing. We are building a **Multi-Tenant** application.

## 1. What is Multi-Tenancy?
Multi-tenancy is an architecture where a single instance of software serves multiple customers (tenants). In our case:
-   **Software**: The Hospital Management System (HMS).
-   **Tenant**: A specific Clinic or Hospital.

Each Clinic thinks they have the application all to themselves. They cannot see other clinics' patients, doctors, or financial data.

## 2. Approaches to Multi-Tenancy
There are three common ways to do this in Laravel:

### A. Multi-Database (Database per Tenant)
-   **Concept**: Every clinic has its own separate MySQL database.
-   **Pros**: Perfect data isolation. Easy to backup/restore a single client.
-   **Cons**: Expensive (resources), hard to maintain (running migrations on 100 databases), complex connection switching.

### B. Single-Database (Column-based)
-   **Concept**: All data lives in one database. Every table has a `clinic_id` column.
-   **Pros**: Easy to maintain, cheap, simple backups.
-   **Cons**: Requires careful coding to ensure `where clinic_id = X` is ALWAYS applied. **This is what we are building.**

### C. Hybrid
-   A mix of both.

## 3. Our Strategy: The "Global Scope" Guard
Since we chose **Single-Database**, the biggest risk is a developer forgetting to add `->where('clinic_id', $id)` to a query, accidentally exposing data.

To solve this, we use Laravel's **Global Scopes**.
-   We defined `BelongsToClinic` trait in Class 05.
-   We defined `BaseTenantModel` in Class 05.
-   We defined `TenantContext` in Class 04.

## 4. The Request Lifecycle in our HMS
Here is how a request will be handled:

1.  **Request Comes In**: `GET /patients`
2.  **Middleware Runs**: `EnsureClinicContext` (We will build this in Class 11) identifies the user's clinic and calls `TenantContext::setClinicId(1)`.
3.  **Controller Runs**: `Patient::all()` is called.
4.  **Model Scope Runs**: The `Patient` model sees it extends `BaseTenantModel`. It checks `TenantContext`. It automatically appends `where clinic_id = 1` to the SQL query.
5.  **Response**: Only patients for Clinic 1 are returned.

## Summary
We are using a **Single-Database, Shared-Schema** approach secured by **Global Query Scopes**. This gives us the best balance of maintainability and security for an HMS.

In the next class, we will implement the `Clinic` model—the entity that represents our tenants.
