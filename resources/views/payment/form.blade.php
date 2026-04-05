@extends('layouts.app')

@section('title', 'Paiement - QuincaPro')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif
        
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                <h1 class="text-2xl font-bold text-white">Souscrire à QuincaPro</h1>
                <p class="text-white text-opacity-90 mt-1">Activez votre abonnement en quelques secondes</p>
            </div>
            
            <div class="p-6">
                <div class="mb-8 border-b pb-4">
                    <h2 class="text-lg font-semibold mb-2">Résumé</h2>
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-gray-600">Formule Mensuelle</span>
                            <p class="text-sm text-gray-500">Accès illimité à toutes les fonctionnalités</p>
                        </div>
                        <div class="text-right">
                            <span class="text-2xl font-bold text-orange-600">{{ number_format($amount, 0) }} XOF</span>
                            <p class="text-xs text-gray-500">~{{ round($amount / 655, 2) }} €</p>
                        </div>
                    </div>
                </div>
                
                <form action="{{ route('payment.initialize') }}" method="POST">
                    @csrf
                    <input type="hidden" name="amount" value="{{ $amount }}">
                    <input type="hidden" name="currency" value="{{ $currency }}">
                    
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-3">Moyen de paiement</label>
                        
                        <div class="space-y-3">
                            <div class="border rounded-lg hover:bg-gray-50">
                                <label class="flex items-center p-3 cursor-pointer">
                                    <input type="radio" name="payment_method" value="mtn" class="mr-3" required>
                                    <div class="flex-1">
                                        <div class="font-semibold">MTN Mobile Money</div>
                                        <div class="text-sm text-gray-500">Paiement mobile instantané</div>
                                    </div>
                                    <div class="text-2xl">📱</div>
                                </label>
                            </div>
                            
                            <div class="border rounded-lg hover:bg-gray-50">
                                <label class="flex items-center p-3 cursor-pointer">
                                    <input type="radio" name="payment_method" value="moov" class="mr-3">
                                    <div class="flex-1">
                                        <div class="font-semibold">Moov Money (T-Money)</div>
                                        <div class="text-sm text-gray-500">Paiement via Moov</div>
                                    </div>
                                    <div class="text-2xl">📱</div>
                                </label>
                            </div>
                            
                            <div class="border rounded-lg hover:bg-gray-50">
                                <label class="flex items-center p-3 cursor-pointer">
                                    <input type="radio" name="payment_method" value="card" class="mr-3">
                                    <div class="flex-1">
                                        <div class="font-semibold">Carte Bancaire</div>
                                        <div class="text-sm text-gray-500">Visa, Mastercard</div>
                                    </div>
                                    <div class="text-2xl">💳</div>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold py-3 rounded-lg hover:shadow-lg transition">
                        Payer {{ number_format($amount, 0) }} XOF
                    </button>
                </form>
            </div>
        </div>
        
        @if(config('services.fedapay.mode') === 'sandbox')
        <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-3">
            <p class="text-xs text-yellow-700 text-center">
                🔧 Mode test - Utilisez le numéro <strong>01010101</strong> pour tester MTN
            </p>
        </div>
        @endif
    </div>
</div>
@endsection
