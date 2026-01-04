# HMS Project Build Guide

This documentation provides a comprehensive, step-by-step tutorial on how to build the Hospital Management System (HMS) from scratch. It is split into 6 detailed sections.

## Table of Contents

1.  [**01. Architecture & Setup**](./01_Architecture_and_Setup.md)
    *   Project structure, Tech stack, and Multi-tenancy implementation.
2.  [**02. Authentication & RBAC**](./02_Auth_and_RBAC.md)
    *   Role-Based Access Control system, Database schema, and Permissions.
3.  [**03. Core Business Logic (Services)**](./03_Business_Logic_Services.md)
    *   Deep dive into `AppointmentService`, `PharmacyService`, `BillingService`, and `IpdService` with full code.
4.  [**04. HTTP Layer (Controllers)**](./04_Http_Layer.md)
    *   Connecting Routes to Controllers and using Services.
5.  [**05. Frontend Implementation**](./05_Frontend_Implementation.md)
    *   Blade layouts, Role-based Sidebars, and Views.
6.  [**06. Seeding & Deployment**](./06_Seeding_and_Deployment.md)
    *   Setting up initial data and running the application.

## How to use this guide
Follow the files in order (01 to 06). Each file contains exact code snippets that you can copy to replicate the functionality of the HMS.
