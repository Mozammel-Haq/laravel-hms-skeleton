# Class 78: Task Scheduling

## Introduction
Some tasks happen automatically: sending appointment reminders, checking for expired medicines, backing up the database.

## 1. The Scheduler
In `routes/console.php`.

```php
use Illuminate\Support\Facades\Schedule;

// 1. Send Appointment Reminders (Every morning at 8 AM)
Schedule::command('appointments:send-reminders')->dailyAt('08:00');

// 2. Check Expired Medicine (Daily)
Schedule::command('pharmacy:check-expiry')->daily();

// 3. Clean up Old Logs (Weekly)
Schedule::command('activitylog:clean')->weekly();
```

## 2. Server Setup
You need one Cron entry on your server:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## Summary
Automation saves time and reduces human error.
