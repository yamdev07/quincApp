<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    <section class="sp-delete-section">
        <header class="sp-delete-header">
            <div class="sp-delete-header-content">
                <h2 class="sp-delete-title">
                    <svg viewBox="0 0 24 24" stroke-width="2" class="sp-delete-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    {{ __('Supprimer le compte') }}
                </h2>
                <p class="sp-delete-description">
                    {{ __('Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées. Avant de supprimer votre compte, veuillez télécharger toutes les données que vous souhaitez conserver.') }}
                </p>
            </div>
        </header>

        <div class="sp-delete-body">
            <div class="sp-delete-warning">
                <svg viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div>
                    <h4>Attention : Action irréversible</h4>
                    <p>Cette action supprimera définitivement votre compte et toutes les données associées. Aucune récupération ne sera possible.</p>
                </div>
            </div>

            <div class="sp-delete-action">
                <button
                    x-data
                    x-on:click="$dispatch('open-modal', 'confirm-user-deletion')"
                    class="sp-btn-danger"
                >
                    <svg viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    {{ __('Supprimer mon compte') }}
                </button>
                <p class="sp-delete-note">Cette action est irréversible. Veuillez être certain de votre décision.</p>
            </div>
        </div>

        {{-- MODAL DE CONFIRMATION --}}
        <div x-data="{ show: false }" 
             x-on:open-modal.window="if ($event.detail === 'confirm-user-deletion') show = true"
             x-on:close-modal.window="show = false"
             x-show="show"
             x-cloak
             class="sp-modal-overlay">
            
            <div class="sp-modal-container" x-on:click.away="show = false">
                <div class="sp-modal">
                    {{-- En-tête du modal --}}
                    <div class="sp-modal-header">
                        <h3 class="sp-modal-title">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            {{ __('Confirmation de suppression') }}
                        </h3>
                        <button type="button" x-on:click="show = false" class="sp-modal-close">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Corps du modal --}}
                    <form wire:submit="deleteUser" class="sp-modal-body">
                        <div class="sp-modal-warning">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div>
                                <h4>⚠️ Action irréversible</h4>
                                <p>Vous êtes sur le point de supprimer définitivement votre compte. Cette action est irréversible et toutes vos données seront perdues.</p>
                            </div>
                        </div>

                        <p class="sp-modal-text">
                            {{ __('Veuillez entrer votre mot de passe pour confirmer que vous souhaitez supprimer définitivement votre compte.') }}
                        </p>

                        <div class="sp-form-group">
                            <label for="password" class="sp-form-label">
                                <svg viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                {{ __('Mot de passe') }}
                            </label>
                            <div class="sp-field-wrapper">
                                <span class="sp-field-ico">
                                    <svg viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </span>
                                <input wire:model="password"
                                       id="password"
                                       name="password"
                                       type="password"
                                       class="sp-form-input"
                                       placeholder="Votre mot de passe"
                                       required>
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

                        {{-- Actions --}}
                        <div class="sp-modal-actions">
                            <button type="button" x-on:click="show = false" class="sp-btn-secondary">
                                <svg viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                {{ __('Annuler') }}
                            </button>

                            <button type="submit" class="sp-btn-danger">
                                <svg viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                {{ __('Supprimer définitivement') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Variables Inventix */
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
            --danger-light: #fee2e2;
            --danger-soft: #fecaca;
            --info: #2563eb;
            --shadow-sm: 0 1px 3px rgba(15,23,42,.06), 0 1px 2px rgba(15,23,42,.04);
            --shadow-md: 0 4px 16px rgba(15,23,42,.08);
            --shadow-danger: 0 8px 24px rgba(220,38,38,.25);
            --radius: 20px;
            --radius-sm: 12px;
        }

        /* Section */
        .sp-delete-section {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-md);
            overflow: hidden;
            transition: all 0.2s ease;
            width: 100%;
        }

        .sp-delete-section:hover {
            border-color: var(--danger-soft);
        }

        /* Header */
        .sp-delete-header {
            padding: 24px 32px;
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            border-bottom: 1px solid var(--border);
        }

        .sp-delete-header-content {
            color: #991b1b;
        }

        .sp-delete-title {
            font-size: 20px;
            font-weight: 700;
            color: #991b1b;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 4px;
        }

        .sp-delete-icon {
            width: 24px;
            height: 24px;
            stroke: #991b1b;
            fill: none;
        }

        .sp-delete-description {
            font-size: 13px;
            color: #7f1d1d;
            line-height: 1.5;
        }

        /* Body */
        .sp-delete-body {
            padding: 32px;
        }

        /* Warning */
        .sp-delete-warning {
            display: flex;
            gap: 16px;
            padding: 20px;
            background: #fff7ed;
            border: 1px solid #fed7aa;
            border-radius: var(--radius-sm);
            margin-bottom: 24px;
        }

        .sp-delete-warning svg {
            width: 24px;
            height: 24px;
            stroke: #c2410c;
            fill: none;
            flex-shrink: 0;
        }

        .sp-delete-warning h4 {
            font-size: 15px;
            font-weight: 700;
            color: #7b341e;
            margin-bottom: 4px;
        }

        .sp-delete-warning p {
            font-size: 13px;
            color: #9a3412;
            line-height: 1.5;
        }

        /* Action */
        .sp-delete-action {
            text-align: center;
        }

        .sp-delete-note {
            margin-top: 12px;
            font-size: 12px;
            color: var(--text-3);
        }

        /* Bouton danger */
        .sp-btn-danger {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 32px;
            background: linear-gradient(135deg, var(--danger), #b91c1c);
            border: none;
            border-radius: 40px;
            font-size: 15px;
            font-weight: 600;
            color: #fff;
            cursor: pointer;
            box-shadow: var(--shadow-danger);
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .sp-btn-danger svg {
            width: 18px;
            height: 18px;
            stroke: #fff;
            fill: none;
        }

        .sp-btn-danger::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.15), transparent);
            transform: translateX(-100%);
            transition: transform 0.5s;
        }

        .sp-btn-danger:hover::after {
            transform: translateX(100%);
        }

        .sp-btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(220, 38, 38, 0.4);
        }

        .sp-btn-danger:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        /* Bouton secondaire */
        .sp-btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: var(--card);
            border: 1.5px solid var(--border);
            border-radius: 40px;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-2);
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .sp-btn-secondary svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
            fill: none;
        }

        .sp-btn-secondary:hover {
            border-color: var(--text-2);
            color: var(--text);
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        /* Modal */
        .sp-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(4px);
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
            animation: fadeIn 0.2s ease;
        }

        .sp-modal-container {
            width: 100%;
            max-width: 500px;
            animation: slideUp 0.3s ease;
        }

        .sp-modal {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-md);
            overflow: hidden;
        }

        .sp-modal-header {
            padding: 20px 24px;
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border);
        }

        .sp-modal-title {
            font-size: 18px;
            font-weight: 700;
            color: #991b1b;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sp-modal-title svg {
            width: 22px;
            height: 22px;
            stroke: #991b1b;
            fill: none;
        }

        .sp-modal-close {
            background: none;
            border: none;
            color: #991b1b;
            cursor: pointer;
            padding: 4px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }

        .sp-modal-close:hover {
            background: rgba(153, 27, 27, 0.1);
        }

        .sp-modal-body {
            padding: 24px;
        }

        .sp-modal-warning {
            display: flex;
            gap: 12px;
            padding: 16px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: var(--radius-sm);
            margin-bottom: 20px;
        }

        .sp-modal-warning svg {
            width: 20px;
            height: 20px;
            stroke: var(--danger);
            fill: none;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .sp-modal-warning h4 {
            font-size: 14px;
            font-weight: 700;
            color: #991b1b;
            margin-bottom: 2px;
        }

        .sp-modal-warning p {
            font-size: 12px;
            color: #7f1d1d;
            line-height: 1.5;
        }

        .sp-modal-text {
            font-size: 14px;
            color: var(--text-2);
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .sp-modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
        }

        /* Form */
        .sp-form-group {
            margin-bottom: 20px;
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
            stroke: var(--danger);
            fill: none;
        }

        .sp-field-wrapper {
            position: relative;
        }

        .sp-field-ico {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-3);
            transition: color 0.2s;
        }

        .sp-field-ico svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            fill: none;
        }

        .sp-field-wrapper:focus-within .sp-field-ico {
            color: var(--danger);
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
            border-color: var(--danger);
            outline: none;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
            background: var(--card);
        }

        .sp-form-input::placeholder {
            color: var(--text-3);
            font-size: 14px;
        }

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

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Cloak */
        [x-cloak] {
            display: none !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sp-delete-header {
                padding: 20px 24px;
            }

            .sp-delete-body {
                padding: 24px;
            }
        }

        @media (max-width: 640px) {
            .sp-delete-section {
                border-radius: 0;
                box-shadow: none;
            }
            
            .sp-delete-header {
                padding: 20px;
            }
            
            .sp-delete-body {
                padding: 20px;
            }
            
            .sp-modal-actions {
                flex-direction: column-reverse;
            }
            
            .sp-btn-secondary,
            .sp-btn-danger {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</div>