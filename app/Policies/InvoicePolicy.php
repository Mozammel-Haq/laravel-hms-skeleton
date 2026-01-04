<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Invoice;

class InvoicePolicy extends BaseTenantPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_invoices');
    }

    public function view(User $user, Invoice $invoice): bool
    {
        return $this->sameClinic($user, $invoice);
    }

    public function create(User $user): bool
    {
        return !empty($user->clinic_id);
    }

    public function update(User $user, Invoice $invoice): bool
    {
        return $this->sameClinic($user, $invoice);
    }

    public function delete(User $user, Invoice $invoice): bool
    {
        return $this->sameClinic($user, $invoice);
    }
}
