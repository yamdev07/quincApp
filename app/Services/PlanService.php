<?php

namespace App\Services;

use App\Models\Tenant;

class PlanService
{
    // ── Limites par plan ─────────────────────────────────────────────────────
    const LIMITS = [
        'starter' => [
            'max_products' => 200,
            'max_users'    => 3,
            'ai_analysis'  => false,
            'pdf_export'   => false,
            'normalized_invoice' => false,
        ],
        'business' => [
            'max_products' => -1,  // illimité
            'max_users'    => 10,
            'ai_analysis'  => true,
            'pdf_export'   => true,
            'normalized_invoice' => true,
        ],
        'pro' => [
            'max_products' => -1,
            'max_users'    => -1,
            'ai_analysis'  => true,
            'pdf_export'   => true,
            'normalized_invoice' => true,
        ],
    ];

    public function __construct(private Tenant $tenant) {}

    public static function for(Tenant $tenant): self
    {
        return new self($tenant);
    }

    private function limit(string $key): mixed
    {
        $plan = $this->plan();
        return self::LIMITS[$plan][$key] ?? self::LIMITS['business'][$key];
    }

    // ── Vérifications ────────────────────────────────────────────────────────

    public function canAddProduct(int $currentCount): bool
    {
        $max = $this->limit('max_products');
        return $max === -1 || $currentCount < $max;
    }

    public function canAddUser(int $currentCount): bool
    {
        $max = $this->limit('max_users');
        return $max === -1 || $currentCount < $max;
    }

    public function canUseAI(): bool
    {
        return (bool) $this->limit('ai_analysis');
    }

    public function canExportPDF(): bool
    {
        return (bool) $this->limit('pdf_export');
    }

    public function canUseNormalizedInvoice(): bool
    {
        return (bool) $this->limit('normalized_invoice');
    }

    // ── Infos utiles pour les vues ───────────────────────────────────────────

    public function maxProducts(): int  { return $this->limit('max_products'); }
    public function maxUsers(): int     { return $this->limit('max_users'); }
    public function plan(): string
    {
        $plan = $this->tenant->plan ?? null;

        // Sécurité : si plan null ou incohérent avec le prix, dériver du prix
        if (!$plan || !isset(self::LIMITS[$plan])) {
            $price = (float) ($this->tenant->subscription_price ?? 0);
            if ($price <= 10000) return 'starter';
            if ($price <= 15000) return 'business';
            return 'pro';
        }

        return $plan;
    }

    public function planLabel(): string
    {
        return match($this->plan()) {
            'starter'  => 'Starter',
            'business' => 'Business',
            'pro'      => 'Pro',
            default    => 'Business',
        };
    }

    public function isStarter(): bool  { return $this->plan() === 'starter'; }
    public function isBusiness(): bool { return $this->plan() === 'business'; }
    public function isPro(): bool      { return $this->plan() === 'pro'; }
}
