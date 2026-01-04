<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Payment;

class PaymentPolicy extends BaseTenantPolicy
{
    public function view(User $user, Payment $payment): bool
    {
        return $this->sameClinic($user, $payment);
    }

    public function create(User $user): bool
    {
        return !empty($user->clinic_id);
    }

    public function update(User $user, Payment $payment): bool
    {
        return $this->sameClinic($user, $payment);
    }

    public function delete(User $user, Payment $payment): bool
    {
        return $this->sameClinic($user, $payment);
    }
}
