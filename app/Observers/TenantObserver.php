<?php

namespace App\Observers;

use App\Models\Tenant;

class TenantObserver
{
    public function saving(Tenant $tenant): void
    {
        $tenant->plan = $this->resolvePlan(
            (string) ($tenant->billing_cycle ?? ''),
            (float)  ($tenant->subscription_price ?? 0)
        );
    }

    private function resolvePlan(string $cycle, float $price): string
    {
        if ($cycle === 'starter' || $price <= 10000) {
            return 'starter';
        }

        if ($cycle === 'lifetime' || $price >= 300000) {
            return 'pro';
        }

        if ($price <= 15000) {
            return 'business';
        }

        return 'pro';
    }
}
