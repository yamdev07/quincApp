<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

@push('styles')
<style>
    html, body {
        margin: 0 !important;
        padding: 0 !important;
        height: 100% !important;
        overflow: auto !important;
        background: #0c0c0f !important;
    }
    body > div {
        min-height: 100vh;
        display: block !important;
        flex-direction: unset !important;
    }

    .reg-page {
        min-height: 100vh;
        display: flex;
        background: #0c0c0f;
        font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif;
    }

    /* ══════════════════════════════
       PANNEAU GAUCHE
    ══════════════════════════════ */
    .reg-left {
        width: 50%;
        display: none;
        flex-direction: column;
        justify-content: space-between;
        padding: 48px 56px;
        background: #111116;
        position: relative;
        overflow: hidden;
        border-right: 1px solid rgba(249,115,22,0.1);
    }
    @media (min-width: 1024px) { .reg-left { display: flex; } }

    .reg-grid-bg {
        position: absolute; inset: 0; pointer-events: none;
        background-image:
            linear-gradient(rgba(249,115,22,0.055) 1px, transparent 1px),
            linear-gradient(90deg, rgba(249,115,22,0.055) 1px, transparent 1px);
        background-size: 48px 48px;
    }
    .reg-orb { position: absolute; border-radius: 50%; pointer-events: none; filter: blur(90px); }
    .reg-orb-a { width: 400px; height: 400px; top: -100px; right: -80px; background: rgba(249,115,22,0.1); }
    .reg-orb-b { width: 280px; height: 280px; bottom: 40px; left: -50px; background: rgba(249,115,22,0.06); }

    /* Logo */
    .reg-logo { display: flex; align-items: center; gap: 13px; position: relative; z-index: 2; }
    .reg-logo-hex {
        width: 46px; height: 46px; flex-shrink: 0;
        background: linear-gradient(135deg, #f97316, #ea580c);
        clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 0 28px rgba(249,115,22,0.45);
    }
    .reg-logo-hex svg { width: 22px; height: 22px; stroke: #fff; fill: none; }
    .reg-logo-name { font-size: 20px; font-weight: 700; color: #f5f3ef; letter-spacing: -0.3px; line-height: 1.1; }
    .reg-logo-name em { font-style: normal; color: #f97316; }
    .reg-logo-tag { font-size: 10px; color: #4e4c5a; letter-spacing: 2.5px; text-transform: uppercase; margin-top: 2px; }

    /* Hero */
    .reg-hero { position: relative; z-index: 2; margin-top: 44px; }
    .reg-eyebrow {
        display: inline-flex; align-items: center; gap: 9px;
        font-size: 11px; font-weight: 600; letter-spacing: 2.2px;
        text-transform: uppercase; color: #f97316; margin-bottom: 22px;
    }
    .reg-eyebrow::before { content: ''; display: block; width: 26px; height: 1.5px; background: #f97316; border-radius: 2px; }
    .reg-hero h1 {
        font-size: clamp(28px, 2.9vw, 44px);
        font-weight: 800; line-height: 1.1;
        color: #f5f3ef; letter-spacing: -1px; margin: 0 0 18px;
    }
    .reg-hero h1 .reg-grad {
        background: linear-gradient(118deg, #f97316 0%, #fb923c 55%, #fbbf24 100%);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    }
    .reg-hero-desc { font-size: 15px; color: #55535f; line-height: 1.75; max-width: 390px; margin: 0; }

    /* Steps */
    .reg-steps { margin-top: 44px; position: relative; z-index: 2; display: flex; flex-direction: column; gap: 20px; }
    .reg-step { display: flex; align-items: flex-start; gap: 16px; }
    .reg-step-num {
        width: 32px; height: 32px; flex-shrink: 0; border-radius: 50%;
        background: rgba(249,115,22,0.12); border: 1px solid rgba(249,115,22,0.2);
        display: flex; align-items: center; justify-content: center;
        font-size: 13px; font-weight: 700; color: #f97316;
    }
    .reg-step-title { font-size: 14px; font-weight: 600; color: #ddd9d3; line-height: 1.3; }
    .reg-step-desc { font-size: 12px; color: #55535f; margin-top: 3px; }

    /* Stats */
    .reg-stats {
        display: flex; align-items: center;
        padding-top: 30px; border-top: 1px solid rgba(249,115,22,0.12);
        position: relative; z-index: 2;
    }
    .reg-stat { flex: 1; text-align: center; }
    .reg-stat-n { font-size: 23px; font-weight: 700; color: #f5f3ef; line-height: 1; }
    .reg-stat-n em { font-style: normal; color: #f97316; }
    .reg-stat-l { font-size: 11px; color: #55535f; margin-top: 3px; }
    .reg-stat-sep { width: 1px; height: 38px; background: rgba(249,115,22,0.12); }

    /* ══════════════════════════════
       PANNEAU DROIT
    ══════════════════════════════ */
    .reg-right {
        flex: 1;
        display: flex; align-items: center; justify-content: center;
        padding: 40px 24px;
        background: #0c0c0f;
    }

    .reg-card {
        width: 100%; max-width: 420px;
        background: #111116;
        border: 1px solid rgba(249,115,22,0.13);
        border-radius: 22px;
        padding: 36px 32px;
        position: relative;
        animation: regCardIn 0.5s cubic-bezier(0.16,1,0.3,1) both;
    }
    .reg-card::before {
        content: '';
        position: absolute; top: -1px; left: 22%; right: 22%;
        height: 1px;
        background: linear-gradient(90deg, transparent, #f97316, transparent);
    }
    @keyframes regCardIn {
        from { opacity: 0; transform: translateY(28px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Mobile logo */
    .reg-mobile-logo { display: flex; align-items: center; gap: 10px; justify-content: center; margin-bottom: 26px; }
    @media (min-width: 1024px) { .reg-mobile-logo { display: none; } }

    /* Card head */
    .reg-card-head { text-align: center; margin-bottom: 26px; }
    .reg-pill {
        display: inline-flex; align-items: center; gap: 7px;
        background: rgba(249,115,22,0.1); border: 1px solid rgba(249,115,22,0.22);
        border-radius: 100px; padding: 5px 13px;
        font-size: 11px; font-weight: 600; color: #fb923c;
        letter-spacing: 0.4px; margin-bottom: 14px;
    }
    .reg-dot { width: 6px; height: 6px; border-radius: 50%; background: #f97316; animation: regBlink 1.8s ease-in-out infinite; }
    @keyframes regBlink { 0%,100%{opacity:1;} 50%{opacity:0.2;} }
    .reg-card-head h2 { font-size: 23px; font-weight: 700; color: #f5f3ef; letter-spacing: -0.4px; margin: 0 0 4px; }
    .reg-card-head p { font-size: 13px; color: #55535f; margin: 0; }

    /* Fields */
    .reg-field { margin-bottom: 14px; }
    .reg-field > label {
        display: block; font-size: 11px; font-weight: 600;
        color: #8a87a0; letter-spacing: 0.9px; text-transform: uppercase; margin-bottom: 7px;
    }
    .reg-field-wrap { position: relative; display: flex; align-items: center; }
    .reg-field-ico { position: absolute; left: 13px; pointer-events: none; display: flex; align-items: center; }
    .reg-field-ico svg { width: 16px; height: 16px; stroke: #3a3848; fill: none; transition: stroke 0.2s; }
    .reg-field-wrap:focus-within .reg-field-ico svg { stroke: #f97316; }
    .reg-field-wrap input {
        width: 100%;
        padding: 12px 14px 12px 42px;
        background: #17171e;
        border: 1.5px solid rgba(40,38,51,0.8);
        border-radius: 11px;
        font-size: 14px; color: #e5e2dd;
        font-family: inherit; outline: none;
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        -webkit-appearance: none; appearance: none;
        box-sizing: border-box;
    }
    .reg-field-wrap input::placeholder { color: rgba(56,54,70,0.7); }
    .reg-field-wrap input:focus {
        border-color: #f97316;
        box-shadow: 0 0 0 3.5px rgba(249,115,22,0.13);
        background: #1b1b24;
    }
    .reg-field-wrap input.is-valid { border-color: rgba(34,197,94,0.5); }
    .reg-field-err { font-size: 12px; color: #f87171; margin-top: 5px; }

    /* ── Password strength ── */
    .pw-strength { margin-top: 10px; }

    .pw-bars { display: flex; gap: 4px; margin-bottom: 10px; }
    .pw-bar {
        flex: 1; height: 3px; border-radius: 2px;
        background: rgba(255,255,255,0.06);
        transition: background 0.3s;
    }
    .pw-bar.active-weak   { background: #ef4444; }
    .pw-bar.active-fair   { background: #f97316; }
    .pw-bar.active-good   { background: #eab308; }
    .pw-bar.active-strong { background: #22c55e; }

    .pw-rules { display: flex; flex-direction: column; gap: 6px; }
    .pw-rule {
        display: flex; align-items: center; gap: 8px;
        font-size: 12px; color: #55535f;
        transition: color 0.2s;
    }
    .pw-rule.met { color: #22c55e; }
    .pw-rule-icon {
        width: 16px; height: 16px; border-radius: 50%; flex-shrink: 0;
        border: 1.5px solid #3a3848;
        display: flex; align-items: center; justify-content: center;
        transition: border-color 0.2s, background 0.2s;
    }
    .pw-rule.met .pw-rule-icon {
        border-color: #22c55e;
        background: rgba(34,197,94,0.15);
    }
    .pw-rule-icon svg { width: 9px; height: 9px; stroke: transparent; fill: none; transition: stroke 0.2s; }
    .pw-rule.met .pw-rule-icon svg { stroke: #22c55e; }

    /* Row */
    .reg-row { display: flex; align-items: center; justify-content: space-between; margin-top: 20px; }
    .reg-login-link { font-size: 13px; color: #f97316; text-decoration: none; font-weight: 500; transition: color 0.15s; }
    .reg-login-link:hover { color: #fb923c; }

    /* Button */
    .reg-btn {
        width: 100%; padding: 13.5px; margin-top: 20px;
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        border: none; border-radius: 11px;
        font-family: inherit; font-size: 15px; font-weight: 700; color: #fff;
        cursor: pointer; position: relative; overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
        display: flex; align-items: center; justify-content: center; gap: 9px;
        letter-spacing: 0.1px; box-sizing: border-box;
    }
    .reg-btn svg { width: 16px; height: 16px; stroke: white; fill: none; flex-shrink: 0; }
    .reg-btn::after {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
        transform: translateX(-100%); transition: transform 0.55s ease;
    }
    .reg-btn:hover::after { transform: translateX(100%); }
    .reg-btn:hover { transform: translateY(-2px); box-shadow: 0 16px 40px -8px rgba(249,115,22,0.5); }
    .reg-btn:active { transform: none; box-shadow: none; }

    /* Already have account */
    .reg-footer { text-align: center; font-size: 13px; color: #55535f; margin-top: 18px; }
    .reg-footer a { color: #f97316; font-weight: 600; text-decoration: none; transition: color 0.15s; }
    .reg-footer a:hover { color: #fb923c; }

    /* Security */
    .reg-sec { display: flex; align-items: center; justify-content: center; gap: 6px; margin-top: 16px; font-size: 11px; color: rgba(56,54,70,0.7); }
    .reg-sec svg { width: 12px; height: 12px; stroke: currentColor; fill: none; }
</style>
@endpush

<div class="reg-page">

    {{-- ══════════════════════════
         PANNEAU GAUCHE
    ══════════════════════════ --}}
    <div class="reg-left">
        <div class="reg-grid-bg"></div>
        <div class="reg-orb reg-orb-a"></div>
        <div class="reg-orb reg-orb-b"></div>

        {{-- Logo --}}
        <div class="reg-logo">
            <div class="reg-logo-hex">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/>
                </svg>
            </div>
            <div>
                <div class="reg-logo-name">Sellvantix</div>
                <div class="reg-logo-tag">Gestion de stock</div>
            </div>
        </div>

        {{-- Hero --}}
        <div class="reg-hero">
            <div class="reg-eyebrow">Nouveau compte</div>
            <h1>
                Rejoignez<br>
                la plateforme<br>
                <span class="reg-grad">référence.</span>
            </h1>
            <p class="reg-hero-desc">
                Créez votre espace en moins de 2 minutes et prenez le contrôle de votre entreprise dès aujourd'hui.
            </p>

            {{-- Steps --}}
            <div class="reg-steps">
                <div class="reg-step">
                    <div class="reg-step-num">1</div>
                    <div>
                        <div class="reg-step-title">Créez votre compte</div>
                        <div class="reg-step-desc">Nom, email et mot de passe sécurisé</div>
                    </div>
                </div>
                <div class="reg-step">
                    <div class="reg-step-num">2</div>
                    <div>
                        <div class="reg-step-title">Configurez votre boutique</div>
                        <div class="reg-step-desc">Ajoutez vos produits et catégories</div>
                    </div>
                </div>
                <div class="reg-step">
                    <div class="reg-step-num">3</div>
                    <div>
                        <div class="reg-step-title">Gérez en temps réel</div>
                        <div class="reg-step-desc">Stocks, ventes et équipes centralisés</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats --}}
        <div class="reg-stats">
            <div class="reg-stat">
                <div class="reg-stat-n">500<em>+</em></div>
                <div class="reg-stat-l">Clients actifs</div>
            </div>
            <div class="reg-stat-sep"></div>
            <div class="reg-stat">
                <div class="reg-stat-n">99.9<em>%</em></div>
                <div class="reg-stat-l">Disponibilité</div>
            </div>
            <div class="reg-stat-sep"></div>
            <div class="reg-stat">
                <div class="reg-stat-n">24<em>/7</em></div>
                <div class="reg-stat-l">Support</div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════
         PANNEAU DROIT
    ══════════════════════════ --}}
    <div class="reg-right">
        <div class="reg-card">

            {{-- Mobile logo --}}
            <div class="reg-mobile-logo">
                <div class="reg-logo-hex" style="width:38px;height:38px;">
                    <svg viewBox="0 0 24 24" stroke-width="2" style="width:18px;height:18px;stroke:white;fill:none;">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/>
                    </svg>
                </div>
                <div class="reg-logo-name">Sellvantix</div>
            </div>

            {{-- Header --}}
            <div class="reg-card-head">
                <div class="reg-pill">
                    <span class="reg-dot"></span>
                    Inscription gratuite
                </div>
                <h2>Créer un compte ✨</h2>
                <p>Rejoignez Sellvantix en quelques secondes</p>
            </div>

            <form wire:submit="register">

                {{-- Nom --}}
                <div class="reg-field">
                    <label for="name">Nom complet</label>
                    <div class="reg-field-wrap">
                        <span class="reg-field-ico">
                            <svg viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </span>
                        <input
                            wire:model.live="name"
                            type="text"
                            id="name"
                            name="name"
                            placeholder="Jean Dupont"
                            autocomplete="name"
                            required
                            class="{{ strlen($name) > 1 ? 'is-valid' : '' }}"
                        >
                    </div>
                    @error('name') <p class="reg-field-err">{{ $message }}</p> @enderror
                </div>

                {{-- Email --}}
                <div class="reg-field">
                    <label for="email">Adresse email</label>
                    <div class="reg-field-wrap">
                        <span class="reg-field-ico">
                            <svg viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <input
                            wire:model.live="email"
                            type="email"
                            id="email"
                            name="email"
                            placeholder="nom@entreprise.com"
                            autocomplete="email"
                            required
                            class="{{ str_contains($email, '@') && str_contains($email, '.') ? 'is-valid' : '' }}"
                        >
                    </div>
                    @error('email') <p class="reg-field-err">{{ $message }}</p> @enderror
                </div>

                {{-- Password --}}
                <div class="reg-field">
                    <label for="password">Mot de passe</label>
                    <div class="reg-field-wrap">
                        <span class="reg-field-ico">
                            <svg viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        <input
                            wire:model.live="password"
                            type="password"
                            id="password"
                            name="password"
                            placeholder="••••••••"
                            autocomplete="new-password"
                            required
                        >
                    </div>
                    @error('password') <p class="reg-field-err">{{ $message }}</p> @enderror

                    {{-- Password strength --}}
                    @if(strlen($password) > 0)
                    @php
                        $hasMin     = strlen($password) >= 8;
                        $hasUpper   = preg_match('/[A-Z]/', $password);
                        $hasLower   = preg_match('/[a-z]/', $password);
                        $hasNumber  = preg_match('/[0-9]/', $password);
                        $hasSpecial = preg_match('/[\W_]/', $password);
                        $score = ($hasMin ? 1 : 0) + ($hasUpper ? 1 : 0) + ($hasLower ? 1 : 0) + ($hasNumber ? 1 : 0) + ($hasSpecial ? 1 : 0);
                        $strengthClass = match(true) {
                            $score <= 1 => 'weak',
                            $score == 2 => 'fair',
                            $score == 3 => 'good',
                            default     => 'strong',
                        };
                        $strengthLabel = match($strengthClass) {
                            'weak'   => 'Faible',
                            'fair'   => 'Moyen',
                            'good'   => 'Bon',
                            'strong' => 'Fort',
                        };
                    @endphp
                    <div class="pw-strength">
                        {{-- Barre de force --}}
                        <div class="pw-bars">
                            @for($i = 0; $i < 4; $i++)
                                @php
                                    $fill = match($strengthClass) {
                                        'weak'   => $i < 1,
                                        'fair'   => $i < 2,
                                        'good'   => $i < 3,
                                        'strong' => true,
                                    };
                                @endphp
                                <div class="pw-bar {{ $fill ? 'active-'.$strengthClass : '' }}"></div>
                            @endfor
                        </div>

                        {{-- Règles avec coches --}}
                        <div class="pw-rules">
                            <div class="pw-rule {{ $hasMin ? 'met' : '' }}">
                                <span class="pw-rule-icon">
                                    <svg viewBox="0 0 12 12" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2 6l3 3 5-5"/></svg>
                                </span>
                                8 caractères minimum
                            </div>
                            <div class="pw-rule {{ $hasUpper ? 'met' : '' }}">
                                <span class="pw-rule-icon">
                                    <svg viewBox="0 0 12 12" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2 6l3 3 5-5"/></svg>
                                </span>
                                Une lettre majuscule (A-Z)
                            </div>
                            <div class="pw-rule {{ $hasLower ? 'met' : '' }}">
                                <span class="pw-rule-icon">
                                    <svg viewBox="0 0 12 12" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2 6l3 3 5-5"/></svg>
                                </span>
                                Une lettre minuscule (a-z)
                            </div>
                            <div class="pw-rule {{ $hasNumber ? 'met' : '' }}">
                                <span class="pw-rule-icon">
                                    <svg viewBox="0 0 12 12" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2 6l3 3 5-5"/></svg>
                                </span>
                                Un chiffre (0-9)
                            </div>
                            <div class="pw-rule {{ $hasSpecial ? 'met' : '' }}">
                                <span class="pw-rule-icon">
                                    <svg viewBox="0 0 12 12" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2 6l3 3 5-5"/></svg>
                                </span>
                                Un caractère spécial (!@#$...)
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Confirm Password --}}
                <div class="reg-field">
                    <label for="password_confirmation">Confirmer le mot de passe</label>
                    <div class="reg-field-wrap">
                        <span class="reg-field-ico">
                            <svg viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </span>
                        <input
                            wire:model.live="password_confirmation"
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="••••••••"
                            autocomplete="new-password"
                            required
                            class="{{ strlen($password_confirmation) > 0 && $password === $password_confirmation ? 'is-valid' : '' }}"
                        >
                    </div>
                    @if(strlen($password_confirmation) > 0 && $password !== $password_confirmation)
                        <p class="reg-field-err">Les mots de passe ne correspondent pas.</p>
                    @endif
                    @error('password_confirmation') <p class="reg-field-err">{{ $message }}</p> @enderror
                </div>

                {{-- Submit --}}
                <button type="submit" class="reg-btn">
                    Créer mon compte
                    <svg viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </button>

            </form>

            <p class="reg-footer">
                Déjà inscrit ?
                <a href="{{ route('login') }}" wire:navigate>Se connecter →</a>
            </p>

            <div class="reg-sec">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Inscription sécurisée — vos données sont protégées
            </div>

        </div>
    </div>

</div>