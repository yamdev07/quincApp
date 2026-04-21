<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */
    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | FedaPay Configuration (TEST/SANDBOX - sans webhook)
    |--------------------------------------------------------------------------
    */
    'fedapay' => [
        // Clés API (à obtenir depuis votre dashboard FedaPay en mode test)
        'api_key' => env('FEDAPAY_API_KEY'),
        'public_key' => env('FEDAPAY_PUBLIC_KEY'),
        'secret_key' => env('FEDAPAY_SECRET_KEY'),
        
        // Mode TEST (sandbox)
        'mode' => env('FEDAPAY_MODE', 'sandbox'),
        
        // URL de retour après paiement (pour les tests en local)
        'return_url' => env('FEDAPAY_RETURN_URL', 'http://localhost:8000/payment/callback'),
        
        // URLs API (sandbox)
        'base_url' => 'https://sandbox-api.fedapay.com',
        
        // URL de paiement (pour redirection en test)
        'payment_url' => 'https://sandbox-payment.fedapay.com/pay/',
        
        // Timeout des requêtes
        'timeout' => env('FEDAPAY_TIMEOUT', 30),
        
        // Activer le mode debug pour voir les erreurs
        'debug' => env('FEDAPAY_DEBUG', true),
        
        // Devises supportées en test
        'currencies' => ['XOF', 'XAF', 'CDF', 'GNF'],
        
        // Méthodes de paiement disponibles en test
        'payment_methods' => [
            'card' => 'Carte bancaire',
            'mtn' => 'MTN Mobile Money',
            'moov' => 'Moov Money',
            'wave' => 'Wave',
        ],
        
        // Numéros de test (documentation FedaPay)
        'test_phones' => [
            'mtn' => '01010101',
            'moov' => '01010102',
            'wave' => '01010103',
        ],
        
        // Cartes de test
        'test_cards' => [
            'visa' => '4111111111111111',
            'mastercard' => '5555555555554444',
        ],
    ],

    'groq' => [
        'api_key' => env('GROQ_API_KEY'),
        'model'   => env('GROQ_MODEL', 'llama-3.3-70b-versatile'),
        'base_url' => 'https://api.groq.com/openai/v1',
    ],

];