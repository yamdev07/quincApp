<?php

namespace Tests\Support;

use App\Models\Tenant;
use App\Models\User;

trait CreatesTenant
{
    protected function makeTenant(array $overrides = []): Tenant
    {
        static $i = 0;
        $i++;

        return Tenant::create(array_merge([
            'name'                  => "Quincaillerie Test {$i}",
            'company_name'          => "Test SARL {$i}",
            'subdomain'             => "test-tenant-{$i}",
            'domain'                => "test-tenant-{$i}.localhost",
            'database_name'         => "db_test_{$i}",
            'db_username'           => "user_test_{$i}",
            'db_password'           => "pass_test_{$i}",
            'email'                 => "tenant{$i}@test.com",
            'payment_status'        => 'paid',
            'subscription_end_date' => now()->addYear(),
            'billing_cycle'         => 'monthly',
            'subscription_price'    => 20000,
        ], $overrides));
    }

    protected function makeAdminFor(Tenant $tenant, array $overrides = []): User
    {
        return User::factory()->create(array_merge([
            'role'      => 'admin',
            'tenant_id' => $tenant->id,
        ], $overrides));
    }

    protected function makeCashierFor(Tenant $tenant, array $overrides = []): User
    {
        return User::factory()->create(array_merge([
            'role'      => 'cashier',
            'tenant_id' => $tenant->id,
        ], $overrides));
    }

    protected function makeManagerFor(Tenant $tenant, array $overrides = []): User
    {
        return User::factory()->create(array_merge([
            'role'      => 'manager',
            'tenant_id' => $tenant->id,
        ], $overrides));
    }
}
