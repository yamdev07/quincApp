<?php

namespace App\Policies;

use App\Models\Sale;
use App\Models\User;

class SalePolicy
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
        return true;
    }

    public function view(User $user, Sale $sale): bool
    {
        return $user->tenant_id === $sale->tenant_id;
    }

    public function create(User $user): bool
    {
        return $user->canManageSales();
    }

    public function cancel(User $user, Sale $sale): bool
    {
        if ($user->tenant_id !== $sale->tenant_id) {
            return false;
        }
        // Les caissiers ne peuvent pas annuler
        return in_array($user->role, ['super_admin', 'admin', 'manager', 'storekeeper']);
    }
}
