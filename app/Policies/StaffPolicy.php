<?php

namespace App\Policies;

use App\Models\User;

class StaffPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isOwner();
    }

    public function manage(User $user, User $staff): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
        return $user->isOwner() && $user->umkm_id === $staff->umkm_id && $staff->role === 'staff';
    }
}
