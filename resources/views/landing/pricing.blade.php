{{-- resources/views/landing/pricing.blade.php --}}
@extends('layouts.landing')

@section('content')
<div class="pricing-page">
    <div class="container">
        <h1>Choisissez votre formule</h1>
        <p>14 jours d'essai gratuit, sans engagement</p>
        
        <div class="pricing-grid">
            @foreach($plans as $key => $plan)
                <div class="pricing-card {{ $plan['popular'] ? 'popular' : '' }}">
                    @if($plan['popular'])
                        <div class="popular-badge">⭐ Le plus choisi</div>
                    @endif
                    <h3>{{ $plan['name'] }}</h3>
                    <div class="price">{{ $plan['formatted'] }}</div>
                    <div class="savings">Économie {{ $plan['savings'] }}</div>
                    <ul class="features-list">
                        <li>✓ Toutes les fonctionnalités</li>
                        <li>✓ Support prioritaire</li>
                        <li>✓ Mises à jour incluses</li>
                    </ul>
                    <button class="btn-select-plan" onclick="showRegistrationForm('{{ $key }}')">
                        Choisir
                    </button>
                </div>
            @endforeach
        </div>
        
        {{-- Formulaire d'inscription (caché au départ) --}}
        <div class="registration-form" id="registrationForm" style="display: none;">
            <h2>Créez votre quincaillerie</h2>
            <form action="{{ route('register.tenant') }}" method="POST">
                @csrf
                <input type="hidden" name="plan" id="selectedPlan">
                
                <div class="form-group">
                    <label>Nom de votre quincaillerie</label>
                    <input type="text" name="company_name" required>
                </div>
                
                <div class="form-group">
                    <label>Sous-domaine</label>
                    <div class="input-group">
                        <input type="text" name="subdomain" required>
                        <span>.quincaapp.com</span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Votre nom</label>
                    <input type="text" name="name" required>
                </div>
                
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label>Téléphone</label>
                    <input type="tel" name="phone">
                </div>
                
                <button type="submit" class="btn-primary">Commencer l'essai gratuit</button>
            </form>
        </div>
    </div>
</div>

<script>
function showRegistrationForm(plan) {
    document.getElementById('selectedPlan').value = plan;
    document.getElementById('registrationForm').style.display = 'block';
    // Scroll jusqu'au formulaire
    document.getElementById('registrationForm').scrollIntoView({ behavior: 'smooth' });
}
</script>
@endsection