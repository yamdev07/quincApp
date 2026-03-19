{{-- resources/views/errors/trial-expired.blade.php --}}
@extends('layouts.app')

@section('title', 'Essai expiré')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full text-center">
        <div class="text-6xl mb-4">⏰</div>
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Période d'essai terminée</h1>
        <p class="text-lg text-gray-600 mb-8">
            Votre période d'essai de 14 jours est arrivée à expiration.<br>
            Pour continuer à utiliser QuincaPro, veuillez souscrire un abonnement.
        </p>
        <a href="{{ route('payment.form') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold rounded-full shadow-lg hover:shadow-xl transition-all">
            <i class="bi bi-credit-card"></i>
            Souscrire maintenant
        </a>
    </div>
</div>
@endsection