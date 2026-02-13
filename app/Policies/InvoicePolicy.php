<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;

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
        return $user->hasPermission('create_invoices') && ! empty($user->clinic_id);
    }

    public function update(User $user, Invoice $invoice): bool
    {
        if ($invoice->state === 'finalized') {
            return false;
        }

        return $this->sameClinic($user, $invoice);
    }

    public function delete(User $user, Invoice $invoice): bool
    {
        // Prevent deleting finalized invoices unless they are unpaid
        if ($invoice->state === 'finalized' && $invoice->status !== 'unpaid') {
            return false;
        }

        return $this->sameClinic($user, $invoice);
    }
}
