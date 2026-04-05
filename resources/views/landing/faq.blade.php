{{-- resources/views/landing/faq.blade.php --}}
@extends('layouts.landing')

@section('content')
<div class="faq-page">
    {{-- FAQ HERO SECTION --}}
    <section class="faq-hero">
        <div class="container">
            <div class="hero-content">
                <span class="hero-badge">💡 FAQ</span>
                <h1 class="hero-title">
                    Questions <span class="text-gradient">fréquentes</span>
                </h1>
                <p class="hero-subtitle">
                    Tout ce que vous devez savoir sur QuincaApp
                </p>
            </div>
        </div>
    </section>

    {{-- FAQ MAIN SECTION --}}
    <section class="faq-main-section">
        <div class="container">
            <div class="faq-grid">
                {{-- Column 1 --}}
                <div class="faq-column">
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>Comment fonctionne l'essai gratuit ?</h3>
                            <span class="faq-toggle"><i class="bi bi-plus-lg"></i></span>
                        </div>
                        <div class="faq-answer">
                            <p>Vous bénéficiez de <strong>14 jours d'essai gratuit</strong> avec toutes les fonctionnalités débloquées. Aucune carte bancaire n'est demandée pour démarrer. À l'issue de l'essai, vous pouvez choisir la formule qui vous convient ou résilier à tout moment.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>Puis-je changer de formule ?</h3>
                            <span class="faq-toggle"><i class="bi bi-plus-lg"></i></span>
                        </div>
                        <div class="faq-answer">
                            <p>Oui, vous pouvez <strong>changer de formule à tout moment</strong> depuis votre espace administrateur. La différence de prix vous sera facturée au prorata du temps restant sur votre abonnement en cours.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>Les données sont-elles sauvegardées ?</h3>
                            <span class="faq-toggle"><i class="bi bi-plus-lg"></i></span>
                        </div>
                        <div class="faq-answer">
                            <p>Absolument ! Nous effectuons des <strong>sauvegardes automatiques quotidiennes</strong> avec chiffrement de bout en bout. Vos données sont hébergées en France chez OVHcloud, avec une redondance sur 3 serveurs différents.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>Quels moyens de paiement acceptez-vous ?</h3>
                            <span class="faq-toggle"><i class="bi bi-plus-lg"></i></span>
                        </div>
                        <div class="faq-answer">
                            <p>Nous acceptons : <strong>Carte bancaire</strong> (Visa, Mastercard), <strong>virement SEPA</strong>, <strong>PayPal</strong>, et pour nos clients en Afrique : <strong>Wave</strong> et <strong>Orange Money</strong>. Le paiement est 100% sécurisé.</p>
                        </div>
                    </div>
                </div>

                {{-- Column 2 --}}
                <div class="faq-column">
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>Y a-t-il un engagement ?</h3>
                            <span class="faq-toggle"><i class="bi bi-plus-lg"></i></span>
                        </div>
                        <div class="faq-answer">
                            <p><strong>Aucun engagement !</strong> Vous pouvez résilier à tout moment directement depuis votre espace client. Pas de frais cachés, pas de période de préavis. Ce que vous payez est ce que vous voyez.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>Le support est-il inclus ?</h3>
                            <span class="faq-toggle"><i class="bi bi-plus-lg"></i></span>
                        </div>
                        <div class="faq-answer">
                            <p>Oui, <strong>le support est inclus dans toutes nos formules</strong> ! Vous bénéficiez d'une assistance par email (réponse sous 2h ouvrées) et d'un chat en direct disponible de 9h à 18h du lundi au vendredi.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>Puis-je importer mes données existantes ?</h3>
                            <span class="faq-toggle"><i class="bi bi-plus-lg"></i></span>
                        </div>
                        <div class="faq-answer">
                            <p>Bien sûr ! Nous vous accompagnons pour <strong>importer vos produits, clients et fournisseurs</strong> depuis un fichier Excel ou CSV. Notre équipe peut même le faire pour vous gratuitement lors de votre inscription.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>Est-ce compatible avec mon logiciel de comptabilité ?</h3>
                            <span class="faq-toggle"><i class="bi bi-plus-lg"></i></span>
                        </div>
                        <div class="faq-answer">
                            <p>Oui, QuincaApp s'intègre avec les principaux logiciels de comptabilité : <strong>QuickBooks, Sage, Cegid, et Dolibarr</strong>. Nous proposons également une API REST pour des intégrations personnalisées.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="faq-footer">
                <p>Vous avez d'autres questions ? <a href="{{ route('register.form') }}" class="faq-link">Créez votre compte</a> ou <a href="{{ route('demo') }}" class="faq-link">demandez une démo</a></p>
            </div>
        </div>
    </section>
</div>

<style>
/* FAQ Page Styles - Matching Landing Page Design */
.faq-page {
    background: var(--white);
}

/* FAQ Hero Section */
.faq-hero {
    position: relative;
    background: linear-gradient(135deg, var(--orange-50) 0%, var(--white) 100%);
    padding: 80px 0;
    overflow: hidden;
    text-align: center;
}

.faq-hero .hero-content {
    position: relative;
    z-index: 2;
}

.faq-hero .hero-badge {
    display: inline-block;
    padding: 8px 16px;
    background: var(--white);
    border-radius: 40px;
    font-size: 14px;
    font-weight: 600;
    color: var(--orange-500);
    margin-bottom: 24px;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--orange-200);
}

.faq-hero .hero-title {
    font-size: 48px;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 24px;
    color: var(--gray-900);
}

.faq-hero .text-gradient {
    background: linear-gradient(135deg, var(--orange-500), var(--orange-600));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.faq-hero .hero-subtitle {
    font-size: 18px;
    color: var(--gray-600);
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

/* FAQ Main Section */
.faq-main-section {
    padding: 80px 0;
    background: var(--white);
}

.faq-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 30px;
    max-width: 1000px;
    margin: 0 auto 40px;
}

.faq-column {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.faq-item {
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.3s ease;
    cursor: pointer;
}

.faq-item:hover {
    border-color: var(--orange-300);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    transform: translateY(-2px);
}

.faq-question {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    gap: 16px;
}

.faq-question h3 {
    font-size: 18px;
    font-weight: 600;
    color: var(--gray-900);
    margin: 0;
    line-height: 1.4;
    flex: 1;
}

.faq-toggle {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    background: var(--gray-100);
    border-radius: 50%;
    color: var(--gray-600);
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.faq-toggle i {
    font-size: 14px;
    transition: transform 0.3s ease;
}

.faq-item.active .faq-toggle {
    background: var(--orange-500);
    color: white;
}

.faq-item.active .faq-toggle i {
    transform: rotate(45deg);
}

.faq-answer {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    padding: 0 24px;
}

.faq-item.active .faq-answer {
    max-height: 300px;
    padding: 0 24px 20px 24px;
}

.faq-answer p {
    color: var(--gray-600);
    line-height: 1.6;
    font-size: 15px;
    margin: 0;
}

.faq-answer strong {
    color: var(--orange-600);
    font-weight: 600;
}

.faq-footer {
    text-align: center;
    padding-top: 40px;
    border-top: 1px solid var(--gray-200);
    max-width: 600px;
    margin: 0 auto;
}

.faq-footer p {
    color: var(--gray-600);
    font-size: 15px;
}

.faq-link {
    color: var(--orange-500);
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s;
}

.faq-link:hover {
    color: var(--orange-600);
    text-decoration: underline;
}

/* Responsive */
@media (max-width: 768px) {
    .faq-hero {
        padding: 60px 0;
    }
    
    .faq-hero .hero-title {
        font-size: 32px;
    }
    
    .faq-hero .hero-subtitle {
        font-size: 16px;
    }
    
    .faq-main-section {
        padding: 60px 0;
    }
    
    .faq-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .faq-question {
        padding: 16px 20px;
    }
    
    .faq-question h3 {
        font-size: 16px;
    }
    
    .faq-item.active .faq-answer {
        padding: 0 20px 16px 20px;
    }
    
    .faq-answer p {
        font-size: 14px;
    }
    
    .faq-footer p {
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .faq-hero .hero-title {
        font-size: 28px;
    }
    
    .faq-question {
        padding: 14px 16px;
    }
    
    .faq-question h3 {
        font-size: 15px;
    }
}
</style>

<script>
// FAQ accordion functionality
document.addEventListener('DOMContentLoaded', function() {
    const faqItems = document.querySelectorAll('.faq-item');
    
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        
        question.addEventListener('click', () => {
            // Close all other items
            faqItems.forEach(otherItem => {
                if (otherItem !== item && otherItem.classList.contains('active')) {
                    otherItem.classList.remove('active');
                }
            });
            
            // Toggle current item
            item.classList.toggle('active');
        });
    });
});
</script>
@endsection