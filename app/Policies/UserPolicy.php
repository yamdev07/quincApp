<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function before(User $user): ?bool
    {
        if ($user->isSuperAdminGlobal()) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->canManageUsers();
    }

    public function create(User $user): bool
    {
        return $user->canManageUsers();
    }

    public function update(User $user, User $target): bool
    {
        if (!$user->canManageUsers()) {
            return false;
        }
        // Un admin ne peut modifier que les users de son tenant
        return $user->tenant_id === $target->tenant_id;
    }

    public function delete(User $user, User $target): bool
    {
        if ($user->id === $target->id) {
            return false; // Ne peut pas se supprimer soi-même
        }
        return $user->isSuperAdmin() && $user->tenant_id === $target->tenant_id;
    }
}
