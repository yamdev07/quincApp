<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<div>
    <section class="sp-profile-section">
        <header class="sp-profile-header">
            <div class="sp-profile-header-content">
                <h2 class="sp-profile-title">
                    <svg viewBox="0 0 24 24" stroke-width="2" class="sp-profile-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    {{ __('Informations du profil') }}
                </h2>
                <p class="sp-profile-description">
                    {{ __("Mettez à jour les informations de votre compte et votre adresse email.") }}
                </p>
            </div>
        </header>

        <div class="sp-profile-body">
            <form wire:submit="updateProfileInformation">
                <div class="sp-form-grid">
                    <!-- Champ Nom -->
                    <div class="sp-form-group">
                        <label for="name" class="sp-form-label">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ __('Nom complet') }}
                        </label>
                        <div class="sp-field-wrapper">
                            <span class="sp-field-ico">
                                <svg viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </span>
                            <input wire:model="name" id="name" name="name" type="text" 
                                   class="sp-form-input" 
                                   required autofocus autocomplete="name"
                                   placeholder="Votre nom complet">
                        </div>
                        @error('name')
                            <p class="sp-error-message">
                                <svg viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Champ Email -->
                    <div class="sp-form-group">
                        <label for="email" class="sp-form-label">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            {{ __('Adresse email') }}
                        </label>
                        <div class="sp-field-wrapper">
                            <span class="sp-field-ico">
                                <svg viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </span>
                            <input wire:model="email" id="email" name="email" type="email" 
                                   class="sp-form-input" 
                                   required autocomplete="username"
                                   placeholder="votre@email.com">
                        </div>
                        @error('email')
                            <p class="sp-error-message">
                                <svg viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Vérification email -->
                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                    <div class="sp-verification-box">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            <p class="sp-verification-text">
                                {{ __('Votre adresse email n\'est pas vérifiée.') }}
                                <button wire:click.prevent="sendVerification" class="sp-verification-link">
                                    {{ __('Renvoyer l\'email de vérification') }}
                                </button>
                            </p>

                            @if (session('status') === 'verification-link-sent')
                                <p class="sp-verification-success">
                                    <svg viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ __('Un nouveau lien de vérification a été envoyé à votre adresse email.') }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                <div class="sp-form-actions">
                    <button type="submit" class="sp-btn-primary">
                        <svg viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ __('Enregistrer') }}
                    </button>

                    <div wire:loading wire:target="updateProfileInformation" class="sp-loading-indicator">
                        <svg viewBox="0 0 24 24" stroke-width="2" class="animate-spin">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span>Enregistrement...</span>
                    </div>

                    <div wire:loading.remove wire:target="updateProfileInformation">
                        <x-action-message class="sp-action-message" on="profile-updated">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ __('Enregistré.') }}
                        </x-action-message>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <style>
        /* Variables QuincaApp */
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
            --shadow-sm: 0 1px 3px rgba(15,23,42,.06), 0 1px 2px rgba(15,23,42,.04);
            --shadow-md: 0 4px 16px rgba(15,23,42,.08);
            --shadow-orange: 0 8px 24px rgba(249,115,22,.25);
            --radius: 20px;
            --radius-sm: 12px;
        }

        /* Section principale */
        .sp-profile-section {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-md);
            overflow: hidden;
            transition: all 0.2s ease;
            width: 100%;
        }

        .sp-profile-section:hover {
            border-color: var(--orange-soft);
        }

        /* Header */
        .sp-profile-header {
            padding: 24px 32px;
            background: linear-gradient(135deg, var(--orange), var(--orange-dark));
            border-bottom: 1px solid var(--border);
        }

        .sp-profile-header-content {
            color: white;
        }

        .sp-profile-title {
            font-size: 20px;
            font-weight: 700;
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 4px;
        }

        .sp-profile-icon {
            width: 24px;
            height: 24px;
            stroke: white;
            fill: none;
        }

        .sp-profile-description {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.5;
        }

        /* Body */
        .sp-profile-body {
            padding: 32px;
        }

        /* Grille pour les champs */
        .sp-form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 24px;
        }

        @media (max-width: 640px) {
            .sp-form-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }

        /* Form Group */
        .sp-form-group {
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

        /* Verification box */
        .sp-verification-box {
            margin: 24px 0 16px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: var(--radius-sm);
            padding: 20px;
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        .sp-verification-box svg {
            width: 20px;
            height: 20px;
            stroke: var(--info);
            fill: none;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .sp-verification-text {
            font-size: 13px;
            color: #1e40af;
            line-height: 1.5;
        }

        .sp-verification-link {
            background: none;
            border: none;
            padding: 0;
            font-size: 13px;
            font-weight: 600;
            color: var(--info);
            text-decoration: underline;
            cursor: pointer;
            transition: color 0.2s;
        }

        .sp-verification-link:hover {
            color: #1e40af;
        }

        .sp-verification-success {
            margin-top: 12px;
            padding: 12px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: var(--radius-sm);
            color: #166534;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sp-verification-success svg {
            width: 18px;
            height: 18px;
            stroke: var(--success);
        }

        /* Form Actions */
        .sp-form-actions {
            display: flex;
            align-items: center;
            gap: 20px;
            padding-top: 24px;
            border-top: 1px solid var(--border);
            flex-wrap: wrap;
        }

        .sp-btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 32px;
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
            width: 16px;
            height: 16px;
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
            .sp-profile-header {
                padding: 20px 24px;
            }

            .sp-profile-body {
                padding: 24px;
            }
        }

        @media (max-width: 640px) {
            .sp-profile-section {
                border-radius: 0;
                box-shadow: none;
            }
            
            .sp-profile-header {
                padding: 20px;
            }
            
            .sp-profile-body {
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