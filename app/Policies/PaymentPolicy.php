<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isOwner() || $user->isStaff();
    }

    public function view(User $user, Payment $payment): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
        return $user->umkm_id === $payment->order->umkm_id;
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isOwner() || $user->isStaff();
    }

    public function update(User $user, Payment $payment): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
        return $user->umkm_id === $payment->order->umkm_id;
    }
}
