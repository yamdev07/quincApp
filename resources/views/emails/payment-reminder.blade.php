{{-- resources/views/emails/payment-reminder.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rappel de paiement</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f9fafb;
            margin: 0;
            padding: 40px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.05);
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo h1 {
            color: #f97316;
            font-size: 28px;
        }
        .content {
            color: #374151;
            line-height: 1.6;
        }
        .info-box {
            background: #fff7ed;
            border-left: 4px solid #f97316;
            padding: 20px;
            border-radius: 12px;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
            padding: 12px 28px;
            border-radius: 40px;
            text-decoration: none;
            margin-top: 20px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <h1>QuincaPro</h1>
        </div>
        
        <div class="content">
            <h2>Bonjour {{ $tenant->owner->name ?? 'Propriétaire' }},</h2>
            
            <p>Nous vous rappelons que le paiement de votre abonnement est <strong>en attente</strong>.</p>
            
            <div class="info-box">
                <p><strong>📋 Informations :</strong></p>
                <p>Quincaillerie : {{ $tenant->company_name }}</p>
                <p>Formule : {{ $tenant->billing_cycle_label }}</p>
                <p>Montant : {{ $tenant->formatted_price }}</p>
                @if($tenant->subscription_end_date)
                <p>Date d'expiration : {{ $tenant->subscription_end_date->format('d/m/Y') }}</p>
                @endif
            </div>
            
            <p>Pour éviter toute interruption de service, merci de procéder au règlement dans les plus brefs délais.</p>
            
            <a href="{{ config('app.url') }}/login" class="button">
                Se connecter
            </a>
        </div>
        
        <div class="footer">
            <p>QuincaPro - Solution de gestion pour quincailleries</p>
            <p>© {{ date('Y') }} Tous droits réservés</p>
        </div>
    </div>
</body>
</html>