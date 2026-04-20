<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<div>
    <section class="sp-password-section">
        <header class="sp-password-header">
            <div class="sp-password-header-content">
                <h2 class="sp-password-title">
                    <svg viewBox="0 0 24 24" stroke-width="2" class="sp-password-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    {{ __('Mettre à jour le mot de passe') }}
                </h2>
                <p class="sp-password-description">
                    {{ __('Assurez-vous que votre compte utilise un mot de passe long et aléatoire pour rester sécurisé.') }}
                </p>
            </div>
        </header>

        <div class="sp-password-body">
            <form wire:submit="updatePassword">
                <div class="sp-form-grid">
                    <!-- Mot de passe actuel -->
                    <div class="sp-form-group">
                        <label for="update_password_current_password" class="sp-form-label">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            {{ __('Mot de passe actuel') }}
                        </label>
                        <div class="sp-field-wrapper">
                            <span class="sp-field-ico">
                                <svg viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </span>
                            <input wire:model="current_password" 
                                   id="update_password_current_password" 
                                   name="current_password" 
                                   type="password" 
                                   class="sp-form-input" 
                                   autocomplete="current-password"
                                   placeholder="Votre mot de passe actuel">
                        </div>
                        @error('current_password')
                            <p class="sp-error-message">
                                <svg viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Nouveau mot de passe et Confirmation -->
                    <div class="sp-form-row">
                        <div class="sp-form-group sp-form-group-half">
                            <label for="update_password_password" class="sp-form-label">
                                <svg viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                {{ __('Nouveau mot de passe') }}
                            </label>
                            <div class="sp-field-wrapper">
                                <span class="sp-field-ico">
                                    <svg viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </span>
                                <input wire:model="password" 
                                       id="update_password_password" 
                                       name="password" 
                                       type="password" 
                                       class="sp-form-input" 
                                       autocomplete="new-password"
                                       placeholder="Nouveau mot de passe">
                            </div>
                            @error('password')
                                <p class="sp-error-message">
                                    <svg viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="sp-form-group sp-form-group-half">
                            <label for="update_password_password_confirmation" class="sp-form-label">
                                <svg viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                {{ __('Confirmer') }}
                            </label>
                            <div class="sp-field-wrapper">
                                <span class="sp-field-ico">
                                    <svg viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </span>
                                <input wire:model="password_confirmation" 
                                       id="update_password_password_confirmation" 
                                       name="password_confirmation" 
                                       type="password" 
                                       class="sp-form-input" 
                                       autocomplete="new-password"
                                       placeholder="Confirmer">
                            </div>
                            @error('password_confirmation')
                                <p class="sp-error-message">
                                    <svg viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- Password strength indicator --}}
                    <div class="sp-password-strength" x-data="{ 
                        password: @entangle('password'),
                        get strength() {
                            if (!this.password) return 0;
                            let score = 0;
                            if (this.password.length >= 8) score += 25;
                            if (this.password.match(/[a-z]+/)) score += 25;
                            if (this.password.match(/[A-Z]+/)) score += 25;
                            if (this.password.match(/[0-9]+/)) score += 25;
                            return score;
                        },
                        get strengthClass() {
                            if (this.strength <= 25) return 'weak';
                            if (this.strength <= 50) return 'fair';
                            if (this.strength <= 75) return 'good';
                            return 'strong';
                        }
                    }">
                        <div class="sp-strength-labels">
                            <span class="sp-strength-label" :class="{ 'active': strengthClass === 'weak' }">Faible</span>
                            <span class="sp-strength-label" :class="{ 'active': strengthClass === 'fair' }">Moyen</span>
                            <span class="sp-strength-label" :class="{ 'active': strengthClass === 'good' }">Bon</span>
                            <span class="sp-strength-label" :class="{ 'active': strengthClass === 'strong' }">Fort</span>
                        </div>
                        <div class="sp-strength-bar">
                            <div class="sp-strength-progress" 
                                 :style="{ width: strength + '%' }"
                                 :class="strengthClass"></div>
                        </div>
                        <p class="sp-strength-hint">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Le mot de passe doit contenir au moins 8 caractères, avec des minuscules, majuscules et chiffres.</span>
                        </p>
                    </div>

                    <div class="sp-form-actions">
                        <button type="submit" class="sp-btn-primary">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ __('Mettre à jour le mot de passe') }}
                        </button>

                        <div wire:loading wire:target="updatePassword" class="sp-loading-indicator">
                            <svg viewBox="0 0 24 24" stroke-width="2" class="animate-spin">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <span>Enregistrement...</span>
                        </div>

                        <div wire:loading.remove wire:target="updatePassword">
                            <x-action-message class="sp-action-message" on="password-updated">
                                <svg viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ __('Enregistré.') }}
                            </x-action-message>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <style>
        /* Variables Sellvantix */
        :root {
            --orange: #f97316;
            --orange-dark: #ea580c;
            --orange-pale: #fff7ed;
            --orange-soft: #fed7aa;
            --bg: #f1f5f9;
            --card: #ffffff;
            --border: #e2e8f0;
            --border-light: #f1f5f9;
            --text: #0f172a;
            --text-2: #475569;
            --text-3: #94a3b8;
            --success: #16a34a;
            --danger: #dc2626;
            --info: #2563eb;
            --warning: #f59e0b;
            --shadow-sm: 0 1px 3px rgba(15,23,42,.06), 0 1px 2px rgba(15,23,42,.04);
            --shadow-md: 0 4px 16px rgba(15,23,42,.08);
            --shadow-orange: 0 8px 24px rgba(249,115,22,.25);
            --radius: 20px;
            --radius-sm: 12px;
        }

        /* Section */
        .sp-password-section {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-md);
            overflow: hidden;
            transition: all 0.2s ease;
            width: 100%;
        }

        .sp-password-section:hover {
            border-color: var(--orange-soft);
        }

        /* Header */
        .sp-password-header {
            padding: 24px 32px;
            background: linear-gradient(135deg, var(--orange), var(--orange-dark));
            border-bottom: 1px solid var(--border);
        }

        .sp-password-header-content {
            color: white;
        }

        .sp-password-title {
            font-size: 20px;
            font-weight: 700;
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 4px;
        }

        .sp-password-icon {
            width: 24px;
            height: 24px;
            stroke: white;
            fill: none;
        }

        .sp-password-description {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.5;
        }

        /* Body */
        .sp-password-body {
            padding: 32px;
        }

        /* Form Grid */
        .sp-form-grid {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        /* Form Row pour les champs côte à côte */
        .sp-form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 640px) {
            .sp-form-row {
                grid-template-columns: 1fr;
                gap: 16px;
            }
        }

        /* Form Group */
        .sp-form-group {
            width: 100%;
        }

        .sp-form-group-half {
            width: 100%;
        }

        .sp-form-label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-2);
            margin-bottom: 8px;
        }

        .sp-form-label svg {
            width: 16px;
            height: 16px;
            stroke: var(--orange);
            fill: none;
        }

        /* Field wrapper */
        .sp-field-wrapper {
            position: relative;
            width: 100%;
        }

        .sp-field-ico {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-3);
            transition: color 0.2s;
            z-index: 1;
        }

        .sp-field-ico svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            fill: none;
        }

        .sp-field-wrapper:focus-within .sp-field-ico {
            color: var(--orange);
        }

        .sp-form-input {
            width: 100%;
            padding: 14px 18px 14px 48px;
            background: #fafbfd;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 15px;
            color: var(--text);
            font-family: inherit;
            transition: all 0.2s;
        }

        .sp-form-input:focus {
            border-color: var(--orange);
            outline: none;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
            background: var(--card);
        }

        .sp-form-input::placeholder {
            color: var(--text-3);
            font-size: 14px;
        }

        /* Error message */
        .sp-error-message {
            margin-top: 8px;
            font-size: 13px;
            color: var(--danger);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .sp-error-message svg {
            width: 14px;
            height: 14px;
            stroke: currentColor;
            fill: none;
        }

        /* Password strength */
        .sp-password-strength {
            margin: 8px 0 16px;
            padding: 20px;
            background: #fafbfd;
            border: 1px solid var(--border-light);
            border-radius: var(--radius-sm);
        }

        .sp-strength-labels {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 11px;
            font-weight: 600;
            color: var(--text-3);
        }

        .sp-strength-label {
            transition: color 0.2s;
        }

        .sp-strength-label.active {
            color: var(--text);
            font-weight: 700;
        }

        .sp-strength-bar {
            height: 6px;
            background: var(--border-light);
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 12px;
        }

        .sp-strength-progress {
            height: 100%;
            transition: width 0.3s ease, background-color 0.3s ease;
        }

        .sp-strength-progress.weak {
            background: var(--danger);
        }

        .sp-strength-progress.fair {
            background: var(--warning);
        }

        .sp-strength-progress.good {
            background: var(--info);
        }

        .sp-strength-progress.strong {
            background: var(--success);
        }

        .sp-strength-hint {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            font-size: 12px;
            color: var(--text-3);
            line-height: 1.5;
        }

        .sp-strength-hint svg {
            width: 14px;
            height: 14px;
            stroke: var(--text-3);
            fill: none;
            flex-shrink: 0;
            margin-top: 2px;
        }

        /* Form Actions */
        .sp-form-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding-top: 24px;
            border-top: 1px solid var(--border);
            flex-wrap: wrap;
        }

        .sp-btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 32px;
            background: linear-gradient(135deg, var(--orange), var(--orange-dark));
            border: none;
            border-radius: 40px;
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            cursor: pointer;
            box-shadow: var(--shadow-orange);
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .sp-btn-primary svg {
            width: 18px;
            height: 18px;
            stroke: #fff;
            fill: none;
        }

        .sp-btn-primary::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.15), transparent);
            transform: translateX(-100%);
            transition: transform 0.5s;
        }

        .sp-btn-primary:hover::after {
            transform: translateX(100%);
        }

        .sp-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(249, 115, 22, 0.4);
        }

        .sp-btn-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        /* Loading indicator */
        .sp-loading-indicator {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--text-2);
        }

        .sp-loading-indicator svg {
            width: 16px;
            height: 16px;
            stroke: var(--orange);
            fill: none;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }

        /* Action message */
        .sp-action-message {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 40px;
            font-size: 13px;
            font-weight: 500;
            color: #166534;
        }

        .sp-action-message svg {
            width: 16px;
            height: 16px;
            stroke: var(--success);
            fill: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sp-password-header {
                padding: 20px 24px;
            }

            .sp-password-body {
                padding: 24px;
            }
        }

        @media (max-width: 640px) {
            .sp-password-section {
                border-radius: 0;
                box-shadow: none;
            }
            
            .sp-password-header {
                padding: 20px;
            }
            
            .sp-password-body {
                padding: 20px;
            }
            
            .sp-form-actions {
                flex-direction: column;
                align-items: stretch;
            }
            
            .sp-btn-primary {
                justify-content: center;
            }
            
            .sp-action-message {
                justify-content: center;
            }
        }
    </style>
</div>