<?php

namespace App\Policies;

use App\Models\Umkm;
use App\Models\User;

class UmkmPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    public function view(User $user, Umkm $umkm): bool
    {
        return $user->isSuperAdmin() || $user->umkm_id === $umkm->id;
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    public function update(User $user, Umkm $umkm): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
        return $user->isOwner() && $user->umkm_id === $umkm->id;
    }

    public function delete(User $user, Umkm $umkm): bool
    {
        return $user->isSuperAdmin();
    }
}
