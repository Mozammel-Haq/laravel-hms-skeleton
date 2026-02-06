# Class 28: Image Handling

## Introduction
Doctors need profile photos. Patients need avatars. Clinics need logos. Handling file uploads securely is important.

## 1. Configuration
Ensure `config/filesystems.php` has the `public` disk configured correctly.
Run: `php artisan storage:link`

## 2. The Upload Trait
Instead of writing upload logic in every controller, let's create a Trait.
Create `app/Traits/UploadsImages.php`.

```php
<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait UploadsImages
{
    public function uploadImage(UploadedFile $file, $folder = 'uploads', $disk = 'public')
    {
        $filename = time() . '_' . $file->getClientOriginalName();
        return $file->storeAs($folder, $filename, $disk);
    }
    
    public function deleteImage($path, $disk = 'public')
    {
        if ($path && Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->delete($path);
        }
    }
}
```

## 3. Usage in Controller
In `DoctorController`:

```php
use App\Traits\UploadsImages;

class DoctorController extends Controller
{
    use UploadsImages;

    public function update(Request $request, Doctor $doctor)
    {
        // ... validation ...
        
        if ($request->hasFile('photo')) {
            // Delete old photo
            $this->deleteImage($doctor->user->profile_photo_path);
            
            // Upload new
            $path = $this->uploadImage($request->file('photo'), 'doctors');
            
            // Update User model (since photo is on users table)
            $doctor->user->update(['profile_photo_path' => $path]);
        }
        
        // ...
    }
}
```

## Summary
We created a reusable component for image handling. This keeps our controllers clean and consistent.
