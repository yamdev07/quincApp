<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue sur Inventix</title>
    <style>
        body {
            font-family: 'Inter', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background: #f1f5f9;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0,0,0,0.05);
        }
        .header {
            background: linear-gradient(135deg, #f97316, #ea580c);
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            color: white;
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .content {
            padding: 40px 30px;
        }
        .credentials {
            background: #fff7ed;
            border: 1px solid #fed7aa;
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
        }
        .credential-item {
            display: flex;
            margin-bottom: 15px;
            align-items: center;
        }
        .credential-label {
            width: 120px;
            font-weight: 600;
            color: #475569;
        }
        .credential-value {
            flex: 1;
            font-family: 'Monaco', monospace;
            font-size: 16px;
            background: white;
            padding: 8px 16px;
            border-radius: 8px;
            border: 1px solid #fed7aa;
            color: #ea580c;
            font-weight: 600;
        }
        .button {
            display: inline-block;
            padding: 14px 30px;
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
            text-decoration: none;
            border-radius: 40px;
            font-weight: 600;
            margin: 25px 0 10px;
            box-shadow: 0 4px 12px rgba(249,115,22,0.3);
        }
        .footer {
            padding: 30px;
            text-align: center;
            color: #94a3b8;
            font-size: 13px;
            border-top: 1px solid #e2e8f0;
        }
        .alert {
            background: #fef3c7;
            border: 1px solid #fde047;
            border-radius: 8px;
            padding: 12px 16px;
            color: #92400e;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 20px 0;
        }
        .alert i {
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Bienvenue sur Inventix !</h1>
        </div>
        
        <div class="content">
            <h2 style="margin-top:0;">Bonjour {{ $user->name }},</h2>
            
            <p>Votre entreprise <strong>{{ $user->tenant->company_name ?? 'Inventix' }}</strong> a été créée avec succès !</p>
            
            <p>Vous bénéficiez maintenant d'un essai gratuit de <strong>14 jours</strong> pour découvrir toutes les fonctionnalités de Inventix.</p>
            
            <div class="credentials">
                <h3 style="margin-top:0; color:#ea580c;">🔑 Vos identifiants de connexion</h3>
                
                <div class="credential-item">
                    <span class="credential-label">Email</span>
                    <span class="credential-value">{{ $user->email }}</span>
                </div>
                
                <div class="credential-item">
                    <span class="credential-label">Mot de passe</span>
                    <span class="credential-value">{{ $plainPassword }}</span>
                </div>
            </div>
            
            <div class="alert">
                <i>⚠️</i>
                <span><strong>Important :</strong> Pour des raisons de sécurité, changez votre mot de passe dès votre première connexion.</span>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ route('login') }}" class="button">
                    🔗 Se connecter à mon espace
                </a>
            </div>
            
            <p style="font-size:14px; color:#64748b; margin-top:30px;">
                Si vous n'êtes pas à l'origine de cette inscription, veuillez ignorer cet email ou contacter notre support à support@quincapro.com.
            </p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Inventix. Tous droits réservés.</p>
            <p style="margin-top:10px;">Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
        </div>
    </div>
</body>
</html>