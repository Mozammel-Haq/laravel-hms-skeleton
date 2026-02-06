# Class 79: System Backup Strategy

## Introduction
Data is the most valuable asset. Losing patient records is catastrophic.

## 1. Spatie Backup
We use `spatie/laravel-backup`.

```bash
composer require spatie/laravel-backup
php artisan vendor:publish --provider="Spatie\Backup\BackupServiceProvider"
```

## 2. Configuration
Edit `config/backup.php`.
-   **Source**: Database (MySQL) and Files (storage/app).
-   **Destination**: Local disk or S3 (AWS).

## 3. Running Backups
Manual:
```bash
php artisan backup:run
```

Scheduled (Class 78):
```php
Schedule::command('backup:run')->dailyAt('01:00');
```

## Summary
"If you don't have a backup, you don't have data." - SysAdmin proverb.
