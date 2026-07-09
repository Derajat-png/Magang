<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isOwner() || $user->isStaff();
    }

    public function view(User $user, Category $category): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
        return $category->umkm_id === null || $user->umkm_id === $category->umkm_id;
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isOwner();
    }

    public function update(User $user, Category $category): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
        return $user->isOwner() && $user->umkm_id === $category->umkm_id;
    }

    public function delete(User $user, Category $category): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
        return $user->isOwner() && $user->umkm_id === $category->umkm_id;
    }
}
