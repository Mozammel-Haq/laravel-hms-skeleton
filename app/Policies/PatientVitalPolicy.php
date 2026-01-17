<?php

namespace App\Policies;

use App\Models\PatientVital;
use App\Models\User;

class PatientVitalPolicy
{
    public function create(User $user): bool
    {
        return $user->hasRole('Nurse') || $user->hasRole('Doctor');
    }
}

