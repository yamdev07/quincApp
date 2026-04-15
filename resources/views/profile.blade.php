@extends('layouts.app')

@section('title', 'Mon Profil — Inventix')

@section('styles')
    @livewireStyles
    <style>
        /* Style spécifique à la page de profil */
        .profile-page {
            background: var(--bg, #f1f5f9);
            min-height: calc(100vh - 64px);
            padding: 2rem 1rem;
        }

        .profile-container {
            max-width: 800px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        /* Supprimer les fonds blancs/gris et laisser les composants gérer leur propre style */
        .profile-card-wrapper {
            background: transparent !important;
            box-shadow: none !important;
            padding: 0 !important;
            border: none !important;
        }

        .profile-card-wrapper > div {
            max-width: 100% !important;
        }

        /* Animation d'apparition */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .profile-card-wrapper {
            animation: fadeUp 0.35s ease both;
        }

        .profile-card-wrapper:nth-child(2) {
            animation-delay: 0.1s;
        }

        .profile-card-wrapper:nth-child(3) {
            animation-delay: 0.2s;
        }

        /* Responsive */
        @media (max-width: 640px) {
            .profile-page {
                padding: 1rem 0.75rem;
            }
        }
    </style>
@endsection

@section('content')
<div class="profile-page">
    <div class="profile-container">

        {{-- Mettre à jour les informations du profil --}}
        <div class="profile-card-wrapper">
            <livewire:profile.update-profile-information-form />
        </div>

        {{-- Mettre à jour le mot de passe --}}
        <div class="profile-card-wrapper">
            <livewire:profile.update-password-form />
        </div>

        {{-- Supprimer le compte --}}
        <div class="profile-card-wrapper">
            <livewire:profile.delete-user-form />
        </div>

    </div>
</div>
@endsection

@section('scripts')
    @livewireScripts
@endsection