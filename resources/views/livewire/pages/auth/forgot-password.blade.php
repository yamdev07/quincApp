<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));
            return;
        }

        $this->reset('email');
        session()->flash('status', __($status));
    }
}; ?>

<div class="min-h-screen flex items-center justify-center bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        
        {{-- Logo / Header --}}
        <div class="text-center">
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm0 13c-2.33 0-4.31-1.46-5.11-3.5h10.22c-.8 2.04-2.78 3.5-5.11 3.5z"/>
                    </svg>
                </div>
            </div>
            <h2 class="text-3xl font-extrabold text-white">
                Mot de passe oublié ?
            </h2>
            <p class="mt-2 text-sm text-gray-400">
                Entrez votre adresse email et nous vous enverrons un lien de réinitialisation
            </p>
        </div>

        {{-- Session Status --}}
        @if (session('status'))
            <div class="rounded-md bg-green-500/20 border border-green-500 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-500">{{ session('status') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Formulaire --}}
        <form wire:submit="sendPasswordResetLink" class="mt-8 space-y-6">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-300">
                    Adresse email
                </label>
                <div class="mt-1">
                    <input wire:model="email" id="email" name="email" type="email" autocomplete="email" required 
                        class="appearance-none block w-full px-3 py-2 border border-gray-700 rounded-md shadow-sm placeholder-gray-500 bg-gray-800 text-white focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm"
                        placeholder="votre@email.com">
                </div>
                @error('email')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button type="submit" 
                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all duration-200">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-orange-300 group-hover:text-orange-200" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M14.243 5.757a6 6 0 10-.986 9.284 1 1 0 111.087 1.678A8 8 0 1118 10a3 3 0 01-4.8 2.401 4 4 0 111.6-6.002 1 1 0 01-1.6 1.2 2 2 0 10.8 3.001A3 3 0 0118 10a6 6 0 00-3.757-4.243z" clip-rule="evenodd"/>
                        </svg>
                    </span>
                    Envoyer le lien de réinitialisation
                </button>
            </div>

            <div class="text-center">
                <a href="{{ route('login') }}" class="text-sm text-orange-500 hover:text-orange-400 transition">
                    ← Retour à la connexion
                </a>
            </div>
        </form>

        {{-- Footer --}}
        <div class="text-center text-xs text-gray-500 mt-8">
            Sellvantix © {{ date('Y') }} - Tous droits réservés
        </div>
    </div>
</div>