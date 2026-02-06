# Class 22: Room & Ward Management

## Introduction
Before we can admit patients (IPD), we need to know where to put them. Hospitals have "Wards" (General Ward, ICU, Private Ward) and "Rooms/Beds" inside them.

## 1. Migrations
We need two models: `Ward` and `Room`.

```bash
php artisan make:model Ward -m
php artisan make:model Room -m
```

### Wards Table
```php
Schema::create('wards', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('clinic_id');
    $table->string('name'); // e.g., "ICU", "General Male"
    $table->string('type'); // e.g., "critical_care", "general", "private"
    $table->integer('capacity')->default(0); // Caching total beds
    $table->timestamps();
    $table->softDeletes();
    $table->foreign('clinic_id')->references('id')->on('clinics')->onDelete('cascade');
});
```

### Rooms Table
```php
Schema::create('rooms', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('clinic_id');
    $table->unsignedBigInteger('ward_id');
    $table->string('room_number'); // "101", "ICU-01"
    $table->string('status')->default('available'); // available, occupied, maintenance
    $table->decimal('daily_rate', 10, 2)->default(0);
    $table->timestamps();
    $table->softDeletes();
    
    $table->foreign('clinic_id')->references('id')->on('clinics')->onDelete('cascade');
    $table->foreign('ward_id')->references('id')->on('wards')->onDelete('cascade');
});
```

## 2. Models
Both must extend `BaseTenantModel`.

```php
// app/Models/Ward.php
class Ward extends BaseTenantModel
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = ['name', 'type', 'capacity'];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}

// app/Models/Room.php
class Room extends BaseTenantModel
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = ['ward_id', 'room_number', 'status', 'daily_rate'];

    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }
}
```

## Summary
We established the physical layout of the hospital. This will be crucial for the **IPD (In-Patient Department)** module later.
