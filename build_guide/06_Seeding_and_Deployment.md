# 06. Seeding & Deployment

## 1. Database Seeder
The master seeder orchestrates the setup.

**File:** `database/seeders/DatabaseSeeder.php`

```php
public function run(): void
{
    // 1. Core Data
    $this->call(RolePermissionSeeder::class);
    
    // 2. Demo Data (Only for local/dev)
    if (app()->environment('local')) {
        // Create a Clinic
        $clinic = \App\Models\Clinic::create(['name' => 'General Hospital', 'address' => '123 Main St']);
        
        // Create Admin User
        $admin = \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'clinic_id' => $clinic->id,
        ]);
        $admin->assignRole('Super Admin');
        
        // Create Doctor
        $doctor = \App\Models\User::factory()->create([
            'name' => 'Dr. Smith',
            'email' => 'doctor@example.com',
            'clinic_id' => $clinic->id,
        ]);
        $doctor->assignRole('Doctor');
        
        // Seed Patients, Medicines, etc.
        // $this->call(PatientSeeder::class);
    }
}
```

## 2. Build & Run Steps

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL 8.0+

### Installation

1.  **Clone & Install Dependencies**
    ```bash
    git clone <repo_url>
    cd hms-project
    composer install
    npm install
    ```

2.  **Environment Setup**
    ```bash
    cp .env.example .env
    # Edit .env and set DB_DATABASE, DB_USERNAME, DB_PASSWORD
    php artisan key:generate
    ```

3.  **Database Setup**
    ```bash
    php artisan migrate:fresh --seed
    # This runs the RolePermissionSeeder and creates the admin user
    ```

4.  **Run Application**
    ```bash
    # Terminal 1: Backend
    php artisan serve

    # Terminal 2: Frontend Assets
    npm run dev
    ```

5.  **Access**
    - URL: `http://localhost:8000`
    - Login: `admin@example.com` (password usually `password` in factories)
