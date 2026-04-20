{{-- resources/views/errors/trial-expired.blade.php --}}
@extends('layouts.app')

@section('title', 'Essai expiré - Choisissez votre formule')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        
        {{-- HEADER --}}
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-orange-100 rounded-full mb-6">
                <span class="text-4xl">⏰</span>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                Votre essai gratuit est terminé
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Choisissez la formule qui correspond le mieux à votre activité<br>
                et continuez à profiter de toutes les fonctionnalités.
            </p>
        </div>

        {{-- PLANS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            
            {{-- PLAN MENSUEL --}}
            <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 flex flex-col h-full">
                <div class="p-6 flex flex-col h-full">
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Mensuel</h3>
                        <div class="mb-2">
                            <span class="text-4xl font-black text-orange-600">10 000</span>
                            <span class="text-gray-500">FCFA</span>
                        </div>
                        <p class="text-sm text-gray-500">/ mois</p>
                        <p class="text-xs text-gray-400 mt-2">Paiement mensuel</p>
                    </div>
                    
                    <div class="flex-1 space-y-3 mb-8">
                        <div class="flex items-center gap-2 text-sm text-gray-700">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span>Toutes les fonctionnalités</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-700">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span>Support standard</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-400">
                            <svg class="w-5 h-5 text-gray-300 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-400">Formation non incluse</span>
                        </div>
                    </div>
                    
                    <div class="mt-auto pt-4">
                        <form action="{{ route('payment.callback') }}" method="POST">
                            @csrf
                            <input type="hidden" name="amount" value="10000">
                            <input type="hidden" name="plan_type" value="monthly">
                            <script 
                                src="https://cdn.fedapay.com/checkout.js?v=1.1.7"
                                data-public-key="{{ config('services.fedapay.public_key') }}"
                                data-button-text="Choisir cette formule"
                                data-button-class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold py-3 px-4 rounded-xl transition-all duration-200 transform hover:scale-105 cursor-pointer"
                                data-transaction-amount="10000"
                                data-transaction-description="Abonnement Sellvantix - Formule Mensuelle"
                                data-currency-iso="XOF"
                                data-customer-email="{{ Auth::user()->email }}"
                                data-customer-firstname="{{ explode(' ', Auth::user()->name)[0] ?? '' }}"
                                data-customer-lastname="{{ explode(' ', Auth::user()->name)[1] ?? '' }}">
                            </script>
                        </form>
                    </div>
                </div>
            </div>

            {{-- PLAN TRIMESTRIEL --}}
            <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 flex flex-col h-full">
                <div class="p-6 flex flex-col h-full">
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Trimestriel</h3>
                        <div class="mb-2">
                            <span class="text-4xl font-black text-orange-600">28 500</span>
                            <span class="text-gray-500">FCFA</span>
                        </div>
                        <p class="text-sm text-gray-500">/ 3 mois</p>
                        <div class="inline-block mt-2 px-2 py-1 bg-green-100 rounded-lg">
                            <p class="text-xs text-green-700 font-semibold">Économisez 1 500 FCFA</p>
                        </div>
                    </div>
                    
                    <div class="flex-1 space-y-3 mb-8">
                        <div class="flex items-center gap-2 text-sm text-gray-700">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span>Toutes les fonctionnalités</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-700">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span>Support prioritaire</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-400">
                            <svg class="w-5 h-5 text-gray-300 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-400">Formation non incluse</span>
                        </div>
                    </div>
                    
                    <div class="mt-auto pt-4">
                        <form action="{{ route('payment.callback') }}" method="POST">
                            @csrf
                            <input type="hidden" name="amount" value="28500">
                            <input type="hidden" name="plan_type" value="quarterly">
                            <script 
                                src="https://cdn.fedapay.com/checkout.js?v=1.1.7"
                                data-public-key="{{ config('services.fedapay.public_key') }}"
                                data-button-text="Choisir cette formule"
                                data-button-class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold py-3 px-4 rounded-xl transition-all duration-200 transform hover:scale-105 cursor-pointer"
                                data-transaction-amount="28500"
                                data-transaction-description="Abonnement Sellvantix - Formule Trimestrielle"
                                data-currency-iso="XOF"
                                data-customer-email="{{ Auth::user()->email }}"
                                data-customer-firstname="{{ explode(' ', Auth::user()->name)[0] ?? '' }}"
                                data-customer-lastname="{{ explode(' ', Auth::user()->name)[1] ?? '' }}">
                            </script>
                        </form>
                    </div>
                </div>
            </div>

            {{-- PLAN SEMESTRIEL (POPULAIRE) --}}
            <div class="group bg-gradient-to-b from-orange-50 to-white rounded-2xl shadow-2xl hover:shadow-3xl transition-all duration-300 transform hover:-translate-y-2 flex flex-col h-full relative border-2 border-orange-500">
                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                    <span class="inline-flex items-center gap-1 px-4 py-1 bg-gradient-to-r from-orange-500 to-orange-600 text-white text-sm font-bold rounded-full shadow-lg">
                        ⭐ Le plus populaire
                    </span>
                </div>
                <div class="p-6 pt-8 flex flex-col h-full">
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Semestriel</h3>
                        <div class="mb-2">
                            <span class="text-4xl font-black text-orange-600">54 000</span>
                            <span class="text-gray-500">FCFA</span>
                        </div>
                        <p class="text-sm text-gray-500">/ 6 mois</p>
                        <div class="inline-block mt-2 px-2 py-1 bg-green-100 rounded-lg">
                            <p class="text-xs text-green-700 font-semibold">Économisez 6 000 FCFA</p>
                        </div>
                    </div>
                    
                    <div class="flex-1 space-y-3 mb-8">
                        <div class="flex items-center gap-2 text-sm text-gray-700">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span>Toutes les fonctionnalités</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-700">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span>Support prioritaire</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-700">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span>Formation offerte</span>
                        </div>
                    </div>
                    
                    <div class="mt-auto pt-4">
                        <form action="{{ route('payment.callback') }}" method="POST">
                            @csrf
                            <input type="hidden" name="amount" value="54000">
                            <input type="hidden" name="plan_type" value="semester">
                            <script 
                                src="https://cdn.fedapay.com/checkout.js?v=1.1.7"
                                data-public-key="{{ config('services.fedapay.public_key') }}"
                                data-button-text="Choisir cette formule"
                                data-button-class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold py-3 px-4 rounded-xl transition-all duration-200 transform hover:scale-105 cursor-pointer shadow-lg"
                                data-transaction-amount="54000"
                                data-transaction-description="Abonnement Sellvantix - Formule Semestrielle"
                                data-currency-iso="XOF"
                                data-customer-email="{{ Auth::user()->email }}"
                                data-customer-firstname="{{ explode(' ', Auth::user()->name)[0] ?? '' }}"
                                data-customer-lastname="{{ explode(' ', Auth::user()->name)[1] ?? '' }}">
                            </script>
                        </form>
                    </div>
                </div>
            </div>

            {{-- PLAN ANNUEL --}}
            <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 flex flex-col h-full">
                <div class="p-6 flex flex-col h-full">
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Annuel</h3>
                        <div class="mb-2">
                            <span class="text-4xl font-black text-orange-600">85 000</span>
                            <span class="text-gray-500">FCFA</span>
                        </div>
                        <p class="text-sm text-gray-500">/ an</p>
                        <div class="inline-block mt-2 px-2 py-1 bg-green-100 rounded-lg">
                            <p class="text-xs text-green-700 font-semibold">Économisez 35 000 FCFA</p>
                        </div>
                    </div>
                    
                    <div class="flex-1 space-y-3 mb-8">
                        <div class="flex items-center gap-2 text-sm text-gray-700">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span>Toutes les fonctionnalités</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-700">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span>Support prioritaire</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-700">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span>Formation offerte</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-700">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span>Audit personnalisé</span>
                        </div>
                    </div>
                    
                    <div class="mt-auto pt-4">
                        <form action="{{ route('payment.callback') }}" method="POST">
                            @csrf
                            <input type="hidden" name="amount" value="85000">
                            <input type="hidden" name="plan_type" value="yearly">
                            <script 
                                src="https://cdn.fedapay.com/checkout.js?v=1.1.7"
                                data-public-key="{{ config('services.fedapay.public_key') }}"
                                data-button-text="Choisir cette formule"
                                data-button-class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold py-3 px-4 rounded-xl transition-all duration-200 transform hover:scale-105 cursor-pointer"
                                data-transaction-amount="85000"
                                data-transaction-description="Abonnement Sellvantix - Formule Annuelle"
                                data-currency-iso="XOF"
                                data-customer-email="{{ Auth::user()->email }}"
                                data-customer-firstname="{{ explode(' ', Auth::user()->name)[0] ?? '' }}"
                                data-customer-lastname="{{ explode(' ', Auth::user()->name)[1] ?? '' }}">
                            </script>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- INFORMATIONS COMPLÉMENTAIRES --}}
        <div class="mt-16 max-w-3xl mx-auto">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                    <div>
                        <div class="text-2xl mb-2">🔒</div>
                        <p class="text-sm font-semibold text-gray-800">Paiement sécurisé</p>
                        <p class="text-xs text-gray-600">Par FedaPay</p>
                    </div>
                    <div>
                        <div class="text-2xl mb-2">✅</div>
                        <p class="text-sm font-semibold text-gray-800">Activation immédiate</p>
                        <p class="text-xs text-gray-600">Après paiement</p>
                    </div>
                    <div>
                        <div class="text-2xl mb-2">📧</div>
                        <p class="text-sm font-semibold text-gray-800">Reçu par email</p>
                        <p class="text-xs text-gray-600">Confirmation envoyée</p>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-8">
                <a href="{{ route('super-admin.tenants') }}" class="text-sm text-gray-500 hover:text-gray-700 transition">
                    📞 Contacter l'administrateur
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    /* Animation pour les cartes */
    .group:hover {
        box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.2);
    }
    
    /* Style personnalisé pour le bouton FedaPay */
    .fedapay-button {
        width: 100% !important;
    }
</style>
@endsection