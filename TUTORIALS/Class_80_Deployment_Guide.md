# Class 80: Deployment Guide

## Introduction
Taking the app from `localhost` to `hms.example.com`.

## 1. Environment Optimization
On the production server:

```bash
# 1. Install Dependencies (No Dev)
composer install --optimize-autoloader --no-dev

# 2. Caching
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 3. Permissions
chown -R www-data:www-data storage bootstrap/cache
```

## 2. Nginx Configuration
Serve the `public` folder.

```nginx
server {
    listen 80;
    server_name hms.example.com;
    root /var/www/hms/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
    }
}
```

## 3. SSL (HTTPS)
Use Certbot (Let's Encrypt).
Medical data **MUST** be encrypted in transit.

## Course Conclusion
Congratulations! You have built a complete, enterprise-grade Hospital Management System from scratch.

You have learned:
-   **Architecture**: Multi-tenancy, Repository Pattern, Service Layer.
-   **Core**: Auth, RBAC, Database Design.
-   **Modules**: Patients, Doctors, OPD, IPD, Pharmacy, Lab, Billing.
-   **DevOps**: Testing, CI/CD (basics), Deployment.

This codebase is now a solid foundation for any healthcare facility.
