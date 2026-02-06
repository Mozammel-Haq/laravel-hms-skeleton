# Class 01: Project Initialization & Environment Setup

## Introduction
Welcome to the first class of the Laravel HMS reconstruction course. In this lesson, we will set up the foundation of our application. We will initialize a new Laravel project, configure our environment variables, and establish the directory structure that will support our multi-tenant architecture.

## 1. Prerequisites
Before we begin, ensure you have the following installed:
- PHP 8.1 or higher
- Composer
- MySQL or SQLite (we will use SQLite for development simplicity in this guide, but MySQL for production)
- Node.js & NPM

## 2. Installing Laravel
Open your terminal and run the following command to create a new Laravel project. We will name it `laravel-hms`.

```bash
composer create-project laravel/laravel laravel-hms
cd laravel-hms
```

## 3. Directory Structure Overview
Laravel comes with a standard directory structure. However, for our HMS, we will need to organize our code to handle complexity.

We will focus on the `app/` directory. By default, it looks like this:
```
app/
├── Console/
├── Exceptions/
├── Http/
├── Models/
├── Providers/
```

We will eventually add the following directories to support our architecture:
```
app/
├── Exports/       # For Excel report generation
├── Services/      # For business logic (e.g., AdmissionService)
├── Support/       # For helper classes and traits
├── Policies/      # For authorization logic
├── Observers/     # For model events
```

## 4. Environment Configuration (.env)
The `.env` file holds your sensitive configuration.

### Database Setup
We will use SQLite for this tutorial to avoid complex database server setup.
1. Create a file named `database.sqlite` in the `database/` directory.
2. Open `.env` and configure the database section:

```ini
DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel_hms
# DB_USERNAME=root
# DB_PASSWORD=
```

### Application Config
Set the application name and URL:
```ini
APP_NAME="Laravel HMS"
APP_ENV=local
APP_KEY=base64:... (Generated automatically)
APP_DEBUG=true
APP_URL=http://localhost:8000
```

## 5. Cleaning Up Default Migrations
Laravel ships with default migrations in `database/migrations`. We will delete them and create our own from scratch to ensure we understand every column.

**Action:** Delete all files inside `database/migrations/`.

## 6. Verification
Run the server to ensure everything is working.
```bash
php artisan serve
```
Visit `http://localhost:8000`. You should see the Laravel welcome page.

## Summary
In this class, we:
1.  Created a fresh Laravel project.
2.  Configured the `.env` file for SQLite.
3.  Cleaned up default migrations to prepare for custom schema design.

In the next class, we will design our database architecture and create the core migrations.
