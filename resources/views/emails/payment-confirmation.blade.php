<!DOCTYPE html>
<html>
<head>
    <title>Confirmation de paiement - QuincaPro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .button {
            display: inline-block;
            background: #f97316;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Paiement confirmé !</h1>
        </div>
        
        <div class="content">
            <p>Bonjour <strong>{{ $user->name }}</strong>,</p>
            
            <p>Nous vous confirmons que votre paiement a bien été reçu et que votre abonnement est maintenant actif.</p>
            
            <div style="background: white; padding: 15px; border-radius: 8px; margin: 20px 0;">
                <h3 style="margin-top: 0;">📋 Détails de la transaction</h3>
                <p><strong>Montant :</strong> {{ number_format($transaction->amount / 100, 0) }} XOF</p>
                <p><strong>Date :</strong> {{ now()->format('d/m/Y à H:i') }}</p>
                <p><strong>Transaction :</strong> {{ $transaction->id }}</p>
                <p><strong>Statut :</strong> <span style="color: green;">Approuvé ✅</span></p>
            </div>
            
            <div style="background: #fff3e0; padding: 15px; border-radius: 8px; margin: 20px 0;">
                <h3 style="margin-top: 0;">📅 Votre abonnement</h3>
                <p><strong>Date d'expiration :</strong> {{ $user->subscription_ends_at->format('d/m/Y') }}</p>
                <p><strong>Accès :</strong> Toutes les fonctionnalités</p>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ route('dashboard') }}" class="button">
                    Accéder au tableau de bord →
                </a>
            </div>
            
            <p style="margin-top: 20px;">Merci de votre confiance ! L'équipe QuincaPro reste à votre disposition.</p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} QuincaPro. Tous droits réservés.</p>
            <p>Cet email est un message automatique, merci de ne pas y répondre.</p>
        </div>
    </div>
</body>
</html>