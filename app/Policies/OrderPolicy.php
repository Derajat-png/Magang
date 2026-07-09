<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isOwner() || $user->isStaff();
    }

    public function view(User $user, Order $order): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
        return $user->umkm_id === $order->umkm_id;
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isOwner() || $user->isStaff();
    }

    public function update(User $user, Order $order): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
        return $user->umkm_id === $order->umkm_id;
    }

    public function updateStatus(User $user, Order $order): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
        if ($user->umkm_id !== $order->umkm_id) {
            return false;
        }
        if ($user->isOwner()) {
            return true;
        }
        if ($user->isStaff()) {
            // Staff can only update if current status is pending
            return $order->status === 'pending';
        }
        return false;
    }
}
