<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Clinic;

/**
 * ClinicImage Model
 *
 * Represents an image associated with a clinic (e.g., gallery, facilities).
 *
 * @property int $id
 * @property int $clinic_id
 * @property string $image_path
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Clinic $clinic
 */
class ClinicImage extends Model
{
    use HasFactory;

    protected $fillable = ['clinic_id', 'image_path', 'sort_order'];

    /**
     * Get the clinic that owns the image.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
    protected $casts = [
        'created_at' => 'date',
    ];
}
