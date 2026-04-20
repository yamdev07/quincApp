{{-- resources/views/landing/guide.blade.php --}}
@extends('layouts.landing')

@section('content')
<div class="guide-page">

    {{-- HERO --}}
    <section class="guide-hero">
        <div class="container">
            <div class="guide-hero-content">
                <span class="hero-badge">📖 Guide d'utilisation</span>
                <h1 class="hero-title">
                    Maîtrisez <span class="text-gradient">Sellvantix</span><br>
                    en quelques minutes
                </h1>
                <p class="hero-subtitle">
                    Ce guide vous accompagne pas à pas dans la prise en main d'Sellvantix — de l'inscription
                    à la gestion quotidienne de votre stock, vos ventes et vos rapports.
                </p>
                <div class="guide-search">
                    <i class="bi bi-search"></i>
                    <input type="text" id="guide-search-input" placeholder="Rechercher dans le guide... (ex : ajouter un produit, créer une vente…)" />
                </div>
            </div>
        </div>
    </section>

    {{-- SOMMAIRE + CONTENU --}}
    <section class="guide-body">
        <div class="container">
            <div class="guide-layout">

                {{-- SIDEBAR SOMMAIRE --}}
                <aside class="guide-sidebar" id="guide-sidebar">
                    <div class="sidebar-sticky">
                        <p class="sidebar-label">Dans ce guide</p>
                        <nav class="sidebar-nav">
                            <a href="#demarrage" class="sidebar-link active">
                                <i class="bi bi-rocket-takeoff"></i> Démarrage rapide
                            </a>
                            <a href="#connexion" class="sidebar-link">
                                <i class="bi bi-shield-lock"></i> Connexion & sécurité
                            </a>
                            <a href="#dashboard" class="sidebar-link">
                                <i class="bi bi-speedometer2"></i> Tableau de bord
                            </a>
                            <a href="#produits" class="sidebar-link">
                                <i class="bi bi-box-seam"></i> Produits & stock
                            </a>
                            <a href="#categories" class="sidebar-link">
                                <i class="bi bi-tags"></i> Catégories
                            </a>
                            <a href="#ventes" class="sidebar-link">
                                <i class="bi bi-cart-check"></i> Ventes & factures
                            </a>
                            <a href="#clients" class="sidebar-link">
                                <i class="bi bi-people"></i> Clients
                            </a>
                            <a href="#fournisseurs" class="sidebar-link">
                                <i class="bi bi-truck"></i> Fournisseurs
                            </a>
                            <a href="#rapports" class="sidebar-link">
                                <i class="bi bi-graph-up"></i> Rapports
                            </a>
                            <a href="#utilisateurs" class="sidebar-link">
                                <i class="bi bi-person-gear"></i> Équipe & rôles
                            </a>
                            <a href="#abonnement" class="sidebar-link">
                                <i class="bi bi-credit-card"></i> Abonnement
                            </a>
                            <a href="#faq-guide" class="sidebar-link">
                                <i class="bi bi-question-circle"></i> Questions fréquentes
                            </a>
                        </nav>
                        <div class="sidebar-cta">
                            <p>Besoin d'aide ?</p>
                            <a href="{{ route('faq') }}" class="btn-sidebar-help">
                                <i class="bi bi-chat-dots"></i> Consulter la FAQ
                            </a>
                        </div>
                    </div>
                </aside>

                {{-- CONTENU PRINCIPAL --}}
                <main class="guide-content">

                    {{-- ============================
                         1. DÉMARRAGE RAPIDE
                    ============================ --}}
                    <section class="guide-section" id="demarrage">
                        <div class="section-badge-guide">
                            <i class="bi bi-rocket-takeoff"></i> Démarrage rapide
                        </div>
                        <h2>Commencer avec Sellvantix</h2>
                        <p class="section-intro">
                            En moins de 5 minutes, votre espace est prêt. Voici les 4 étapes pour démarrer.
                        </p>

                        <div class="steps-grid">
                            <div class="step-card">
                                <div class="step-number">1</div>
                                <div class="step-body">
                                    <h4>Créez votre compte</h4>
                                    <p>Rendez-vous sur la page <a href="{{ route('pricing') }}">Tarifs</a>, choisissez une formule et remplissez le formulaire d'inscription. Vous recevez immédiatement vos identifiants par email.</p>
                                </div>
                            </div>
                            <div class="step-card">
                                <div class="step-number">2</div>
                                <div class="step-body">
                                    <h4>Connectez-vous</h4>
                                    <p>Utilisez l'email et le mot de passe reçus par email pour vous connecter. Changez votre mot de passe dès la première connexion depuis votre profil.</p>
                                </div>
                            </div>
                            <div class="step-card">
                                <div class="step-number">3</div>
                                <div class="step-body">
                                    <h4>Ajoutez vos produits</h4>
                                    <p>Créez vos catégories puis ajoutez vos produits avec leurs prix et quantités en stock. Vous pouvez aussi importer depuis un fichier CSV.</p>
                                </div>
                            </div>
                            <div class="step-card">
                                <div class="step-number">4</div>
                                <div class="step-body">
                                    <h4>Effectuez votre 1ère vente</h4>
                                    <p>Allez dans <strong>Ventes → Nouvelle vente</strong>, sélectionnez les produits, le client, et validez. La facture est générée automatiquement.</p>
                                </div>
                            </div>
                        </div>

                        <div class="info-box info-success">
                            <i class="bi bi-check-circle-fill"></i>
                            <div>
                                <strong>Essai gratuit 14 jours</strong>
                                <p>Toutes les fonctionnalités sont disponibles pendant votre essai. Aucune carte bancaire requise.</p>
                            </div>
                        </div>
                    </section>

                    <div class="guide-divider"></div>

                    {{-- ============================
                         2. CONNEXION & SÉCURITÉ
                    ============================ --}}
                    <section class="guide-section" id="connexion">
                        <div class="section-badge-guide">
                            <i class="bi bi-shield-lock"></i> Connexion & sécurité
                        </div>
                        <h2>Se connecter et sécuriser son compte</h2>

                        <div class="guide-feature-block">
                            <div class="feature-block-icon"><i class="bi bi-box-arrow-in-right"></i></div>
                            <div class="feature-block-body">
                                <h3>Se connecter</h3>
                                <p>Accédez à la page de connexion via le bouton <strong>Connexion</strong> en haut à droite du site. Entrez votre adresse email et votre mot de passe.</p>
                                <ol class="guide-steps-list">
                                    <li>Cliquez sur <strong>Connexion</strong> dans la barre de navigation</li>
                                    <li>Entrez votre adresse email</li>
                                    <li>Entrez votre mot de passe</li>
                                    <li>Cliquez sur <strong>Se connecter</strong></li>
                                </ol>
                            </div>
                        </div>

                        <div class="guide-feature-block">
                            <div class="feature-block-icon"><i class="bi bi-key"></i></div>
                            <div class="feature-block-body">
                                <h3>Mot de passe oublié</h3>
                                <p>Si vous avez oublié votre mot de passe, cliquez sur <strong>Mot de passe oublié ?</strong> sur la page de connexion. Un lien de réinitialisation vous sera envoyé par email.</p>
                            </div>
                        </div>

                        <div class="guide-feature-block">
                            <div class="feature-block-icon"><i class="bi bi-person-circle"></i></div>
                            <div class="feature-block-body">
                                <h3>Modifier son profil</h3>
                                <p>Depuis n'importe quelle page de l'application, cliquez sur votre nom en haut à droite, puis sur <strong>Profil</strong>. Vous pouvez y modifier :</p>
                                <ul class="guide-list">
                                    <li>Votre nom et prénom</li>
                                    <li>Votre adresse email</li>
                                    <li>Votre mot de passe</li>
                                    <li>Supprimer votre compte</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <div class="guide-divider"></div>

                    {{-- ============================
                         3. TABLEAU DE BORD
                    ============================ --}}
                    <section class="guide-section" id="dashboard">
                        <div class="section-badge-guide">
                            <i class="bi bi-speedometer2"></i> Tableau de bord
                        </div>
                        <h2>Le tableau de bord</h2>
                        <p class="section-intro">
                            Le tableau de bord est votre page d'accueil après la connexion. Il vous donne une vue d'ensemble instantanée de votre activité.
                        </p>

                        <div class="dashboard-preview-guide">
                            <div class="preview-row-guide">
                                <div class="preview-stat-card">
                                    <i class="bi bi-cart-check"></i>
                                    <span class="preview-stat-label">Ventes du jour</span>
                                    <span class="preview-stat-value">24</span>
                                </div>
                                <div class="preview-stat-card">
                                    <i class="bi bi-currency-dollar"></i>
                                    <span class="preview-stat-label">Chiffre d'affaires</span>
                                    <span class="preview-stat-value">154 500 FCFA</span>
                                </div>
                                <div class="preview-stat-card">
                                    <i class="bi bi-box-seam"></i>
                                    <span class="preview-stat-label">Produits en stock</span>
                                    <span class="preview-stat-value">156</span>
                                </div>
                                <div class="preview-stat-card preview-stat-alert">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    <span class="preview-stat-label">Alertes stock bas</span>
                                    <span class="preview-stat-value">3</span>
                                </div>
                            </div>
                        </div>

                        <div class="guide-cards-grid">
                            <div class="guide-info-card">
                                <div class="guide-info-icon"><i class="bi bi-bar-chart"></i></div>
                                <h4>Graphique des ventes</h4>
                                <p>Visualisez l'évolution de vos ventes sur les 7 derniers jours. Le graphique se met à jour en temps réel.</p>
                            </div>
                            <div class="guide-info-card">
                                <div class="guide-info-icon"><i class="bi bi-clock-history"></i></div>
                                <h4>Dernières ventes</h4>
                                <p>Liste des ventes les plus récentes avec le nom du client, le montant et le statut du paiement.</p>
                            </div>
                            <div class="guide-info-card">
                                <div class="guide-info-icon"><i class="bi bi-exclamation-triangle"></i></div>
                                <h4>Alertes stock bas</h4>
                                <p>Produits dont le stock est inférieur au seuil d'alerte. Cliquez sur un produit pour le réapprovisionner directement.</p>
                            </div>
                            <div class="guide-info-card">
                                <div class="guide-info-icon"><i class="bi bi-trophy"></i></div>
                                <h4>Top produits</h4>
                                <p>Les produits les plus vendus de la période. Utile pour ajuster vos commandes fournisseurs.</p>
                            </div>
                        </div>
                    </section>

                    <div class="guide-divider"></div>

                    {{-- ============================
                         4. PRODUITS & STOCK
                    ============================ --}}
                    <section class="guide-section" id="produits">
                        <div class="section-badge-guide">
                            <i class="bi bi-box-seam"></i> Produits & stock
                        </div>
                        <h2>Gérer vos produits et votre stock</h2>
                        <p class="section-intro">
                            La gestion des produits est le cœur d'Sellvantix. Voici comment ajouter, modifier et suivre votre inventaire.
                        </p>

                        <div class="guide-feature-block">
                            <div class="feature-block-icon"><i class="bi bi-plus-circle"></i></div>
                            <div class="feature-block-body">
                                <h3>Ajouter un produit</h3>
                                <ol class="guide-steps-list">
                                    <li>Dans le menu de gauche, cliquez sur <strong>Produits</strong></li>
                                    <li>Cliquez sur le bouton <strong>+ Nouveau produit</strong></li>
                                    <li>Remplissez les informations : nom, référence, catégorie, fournisseur</li>
                                    <li>Indiquez le prix d'achat, le prix de vente et la quantité initiale</li>
                                    <li>Définissez le <strong>seuil d'alerte stock</strong> (ex. : 5 unités)</li>
                                    <li>Cliquez sur <strong>Enregistrer</strong></li>
                                </ol>
                                <div class="info-box info-tip">
                                    <i class="bi bi-lightbulb-fill"></i>
                                    <div><strong>Astuce :</strong> Le champ <em>seuil d'alerte</em> déclenche une notification automatique sur le tableau de bord quand le stock passe en dessous de ce nombre.</div>
                                </div>
                            </div>
                        </div>

                        <div class="guide-feature-block">
                            <div class="feature-block-icon"><i class="bi bi-arrow-up-circle"></i></div>
                            <div class="feature-block-body">
                                <h3>Réapprovisionner un produit</h3>
                                <p>Quand vous recevez une livraison, vous devez mettre à jour le stock :</p>
                                <ol class="guide-steps-list">
                                    <li>Ouvrez la fiche du produit concerné</li>
                                    <li>Cliquez sur <strong>Réapprovisionner</strong></li>
                                    <li>Entrez la quantité reçue et le prix d'achat unitaire</li>
                                    <li>Sélectionnez le fournisseur (optionnel)</li>
                                    <li>Ajoutez une note si nécessaire</li>
                                    <li>Confirmez — le stock est mis à jour instantanément</li>
                                </ol>
                            </div>
                        </div>

                        <div class="guide-feature-block">
                            <div class="feature-block-icon"><i class="bi bi-sliders"></i></div>
                            <div class="feature-block-body">
                                <h3>Ajuster le stock manuellement</h3>
                                <p>Pour corriger une erreur ou enregistrer une perte/casse, utilisez la correction manuelle :</p>
                                <ol class="guide-steps-list">
                                    <li>Ouvrez la fiche du produit</li>
                                    <li>Cliquez sur <strong>Ajuster le stock</strong></li>
                                    <li>Choisissez le type : <em>Entrée (+)</em> ou <em>Sortie (-)</em></li>
                                    <li>Indiquez la quantité et le motif (perte, correction, inventaire…)</li>
                                    <li>Validez</li>
                                </ol>
                                <div class="info-box info-warning">
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                    <div><strong>Important :</strong> Chaque ajustement est tracé dans l'historique du produit avec la date, l'utilisateur et le motif.</div>
                                </div>
                            </div>
                        </div>

                        <div class="guide-feature-block">
                            <div class="feature-block-icon"><i class="bi bi-clock-history"></i></div>
                            <div class="feature-block-body">
                                <h3>Historique des mouvements</h3>
                                <p>Sur chaque fiche produit, l'onglet <strong>Historique</strong> affiche tous les mouvements de stock (entrées, sorties, ventes, ajustements) avec la date, l'opérateur et la quantité.</p>
                                <p>Vous pouvez aussi consulter l'<strong>historique global</strong> depuis le menu <strong>Produits → Historique global</strong> pour voir tous les mouvements de tous les produits.</p>
                            </div>
                        </div>

                        <div class="guide-feature-block">
                            <div class="feature-block-icon"><i class="bi bi-diagram-3"></i></div>
                            <div class="feature-block-body">
                                <h3>Fusionner des produits</h3>
                                <p>Si vous avez des doublons ou souhaitez regrouper des références similaires, la fusion permet de combiner deux produits en un seul sans perdre l'historique.</p>
                                <ol class="guide-steps-list">
                                    <li>Allez dans <strong>Produits</strong> et sélectionnez les produits à fusionner</li>
                                    <li>Cliquez sur <strong>Fusionner</strong></li>
                                    <li>Choisissez le produit principal (celui qui sera conservé)</li>
                                    <li>Confirmez — les stocks et l'historique sont combinés</li>
                                </ol>
                            </div>
                        </div>
                    </section>

                    <div class="guide-divider"></div>

                    {{-- ============================
                         5. CATÉGORIES
                    ============================ --}}
                    <section class="guide-section" id="categories">
                        <div class="section-badge-guide">
                            <i class="bi bi-tags"></i> Catégories
                        </div>
                        <h2>Organiser vos produits par catégories</h2>
                        <p class="section-intro">
                            Les catégories permettent d'organiser votre catalogue et de retrouver rapidement vos produits. Créez-les avant d'ajouter vos produits.
                        </p>

                        <div class="guide-feature-block">
                            <div class="feature-block-icon"><i class="bi bi-folder-plus"></i></div>
                            <div class="feature-block-body">
                                <h3>Créer une catégorie</h3>
                                <ol class="guide-steps-list">
                                    <li>Dans le menu, cliquez sur <strong>Catégories</strong></li>
                                    <li>Cliquez sur <strong>+ Nouvelle catégorie</strong></li>
                                    <li>Donnez un nom et une description (optionnelle)</li>
                                    <li>Enregistrez</li>
                                </ol>
                                <div class="info-box info-tip">
                                    <i class="bi bi-lightbulb-fill"></i>
                                    <div><strong>Exemples de catégories :</strong> Outils, Visserie, Plomberie, Électricité, Peinture, etc. Adaptez-les à votre type de commerce.</div>
                                </div>
                            </div>
                        </div>

                        <div class="guide-feature-block">
                            <div class="feature-block-icon"><i class="bi bi-bar-chart-line"></i></div>
                            <div class="feature-block-body">
                                <h3>Statistiques par catégorie</h3>
                                <p>Depuis la fiche d'une catégorie, accédez aux <strong>Statistiques détaillées</strong> pour voir :</p>
                                <ul class="guide-list">
                                    <li>Le nombre de produits dans la catégorie</li>
                                    <li>La valeur totale du stock</li>
                                    <li>Les produits les plus vendus</li>
                                    <li>L'évolution des ventes sur la période</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <div class="guide-divider"></div>

                    {{-- ============================
                         6. VENTES & FACTURES
                    ============================ --}}
                    <section class="guide-section" id="ventes">
                        <div class="section-badge-guide">
                            <i class="bi bi-cart-check"></i> Ventes & factures
                        </div>
                        <h2>Enregistrer une vente et générer une facture</h2>
                        <p class="section-intro">
                            Le module ventes est conçu pour aller vite. Une vente complète se crée en moins de 30 secondes.
                        </p>

                        <div class="guide-feature-block">
                            <div class="feature-block-icon"><i class="bi bi-plus-circle"></i></div>
                            <div class="feature-block-body">
                                <h3>Créer une vente</h3>
                                <ol class="guide-steps-list">
                                    <li>Cliquez sur <strong>Ventes → Nouvelle vente</strong></li>
                                    <li>Sélectionnez ou créez le client (vous pouvez aussi vendre sans client enregistré)</li>
                                    <li>Ajoutez les produits en tapant leur nom ou référence dans le champ de recherche</li>
                                    <li>Ajustez les quantités et appliquez d'éventuelles remises</li>
                                    <li>Sélectionnez le mode de paiement (espèces, virement, mobile money…)</li>
                                    <li>Cliquez sur <strong>Valider la vente</strong></li>
                                </ol>
                                <p>Le stock des produits vendus est automatiquement décrémenté.</p>
                            </div>
                        </div>

                        <div class="guide-feature-block">
                            <div class="feature-block-icon"><i class="bi bi-receipt"></i></div>
                            <div class="feature-block-body">
                                <h3>Imprimer ou envoyer la facture</h3>
                                <p>Après validation, une facture PDF est générée. Vous pouvez :</p>
                                <ul class="guide-list">
                                    <li><strong>Imprimer</strong> la facture directement depuis le navigateur</li>
                                    <li><strong>Télécharger</strong> le PDF</li>
                                    <li><strong>Envoyer par email</strong> au client (si son email est renseigné)</li>
                                </ul>
                            </div>
                        </div>

                        <div class="guide-feature-block">
                            <div class="feature-block-icon"><i class="bi bi-pencil-square"></i></div>
                            <div class="feature-block-body">
                                <h3>Modifier ou annuler une vente</h3>
                                <p>Depuis la liste des ventes, cliquez sur une vente pour l'ouvrir. Vous pouvez :</p>
                                <ul class="guide-list">
                                    <li>Modifier les détails (quantité, remise, mode de paiement)</li>
                                    <li>Changer le statut : <em>En attente, Payée, Annulée</em></li>
                                    <li>Supprimer la vente (le stock est remis à jour)</li>
                                </ul>
                                <div class="info-box info-warning">
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                    <div><strong>Attention :</strong> La suppression d'une vente est irréversible. Préférez le statut <em>Annulée</em> pour garder la traçabilité.</div>
                                </div>
                            </div>
                        </div>

                        <div class="guide-feature-block">
                            <div class="feature-block-icon"><i class="bi bi-lightning-charge"></i></div>
                            <div class="feature-block-body">
                                <h3>Vente rapide depuis un produit</h3>
                                <p>Sur la liste des produits, chaque article dispose d'un bouton <strong>Vente rapide</strong>. Il ouvre un panneau latéral pour saisir la quantité vendue et confirmer en un clic — pratique pour les comptoirs très fréquentés.</p>
                            </div>
                        </div>
                    </section>

                    <div class="guide-divider"></div>

                    {{-- ============================
                         7. CLIENTS
                    ============================ --}}
                    <section class="guide-section" id="clients">
                        <div class="section-badge-guide">
                            <i class="bi bi-people"></i> Clients
                        </div>
                        <h2>Gérer vos clients</h2>
                        <p class="section-intro">
                            Centralisez les informations de vos clients et suivez leur historique d'achats pour mieux les fidéliser.
                        </p>

                        <div class="guide-feature-block">
                            <div class="feature-block-icon"><i class="bi bi-person-plus"></i></div>
                            <div class="feature-block-body">
                                <h3>Ajouter un client</h3>
                                <ol class="guide-steps-list">
                                    <li>Cliquez sur <strong>Clients → Nouveau client</strong></li>
                                    <li>Renseignez : nom, téléphone, email, adresse</li>
                                    <li>Enregistrez</li>
                                </ol>
                                <p>Vous pouvez aussi créer un client directement lors de la création d'une vente.</p>
                            </div>
                        </div>

                        <div class="guide-feature-block">
                            <div class="feature-block-icon"><i class="bi bi-person-lines-fill"></i></div>
                            <div class="feature-block-body">
                                <h3>Fiche client</h3>
                                <p>Cliquez sur un client pour accéder à sa fiche complète qui affiche :</p>
                                <ul class="guide-list">
                                    <li>Ses coordonnées</li>
                                    <li>L'historique de toutes ses commandes</li>
                                    <li>Le total dépensé et le nombre de ventes</li>
                                    <li>Les statistiques d'achat (produits préférés, fréquence)</li>
                                </ul>
                            </div>
                        </div>

                        <div class="guide-feature-block">
                            <div class="feature-block-icon"><i class="bi bi-download"></i></div>
                            <div class="feature-block-body">
                                <h3>Exporter la liste des clients</h3>
                                <p>Depuis la liste des clients, cliquez sur <strong>Exporter</strong> pour télécharger un fichier CSV ou Excel avec toutes les informations clients — pratique pour des campagnes marketing ou des sauvegardes.</p>
                            </div>
                        </div>
                    </section>

                    <div class="guide-divider"></div>

                    {{-- ============================
                         8. FOURNISSEURS
                    ============================ --}}
                    <section class="guide-section" id="fournisseurs">
                        <div class="section-badge-guide">
                            <i class="bi bi-truck"></i> Fournisseurs
                        </div>
                        <h2>Gérer vos fournisseurs</h2>
                        <p class="section-intro">
                            Le module fournisseurs vous aide à centraliser vos contacts et à associer chaque produit à sa source d'approvisionnement.
                        </p>

                        <div class="guide-feature-block">
                            <div class="feature-block-icon"><i class="bi bi-building-add"></i></div>
                            <div class="feature-block-body">
                                <h3>Ajouter un fournisseur</h3>
                                <ol class="guide-steps-list">
                                    <li>Cliquez sur <strong>Fournisseurs → Nouveau fournisseur</strong></li>
                                    <li>Renseignez : nom de l'entreprise, contact, téléphone, email, adresse</li>
                                    <li>Ajoutez une note éventuelle (délai de livraison, conditions, etc.)</li>
                                    <li>Enregistrez</li>
                                </ol>
                            </div>
                        </div>

                        <div class="guide-feature-block">
                            <div class="feature-block-icon"><i class="bi bi-link-45deg"></i></div>
                            <div class="feature-block-body">
                                <h3>Associer un fournisseur à un produit</h3>
                                <p>Lors de la création ou modification d'un produit, sélectionnez le fournisseur dans le champ dédié. Vous pouvez ainsi voir depuis la fiche fournisseur la liste de tous les produits qu'il vous fournit.</p>
                            </div>
                        </div>

                        <div class="guide-feature-block">
                            <div class="feature-block-icon"><i class="bi bi-receipt-cutoff"></i></div>
                            <div class="feature-block-body">
                                <h3>Historique des commandes fournisseur</h3>
                                <p>Depuis la fiche fournisseur, l'onglet <strong>Commandes</strong> liste tous les réapprovisionnements effectués chez ce fournisseur — date, produits, quantités et montants.</p>
                            </div>
                        </div>
                    </section>

                    <div class="guide-divider"></div>

                    {{-- ============================
                         9. RAPPORTS
                    ============================ --}}
                    <section class="guide-section" id="rapports">
                        <div class="section-badge-guide">
                            <i class="bi bi-graph-up"></i> Rapports
                        </div>
                        <h2>Analyser votre activité avec les rapports</h2>
                        <p class="section-intro">
                            Les rapports vous donnent une vision claire et chiffrée de votre performance. Accédez-y depuis le menu <strong>Rapports</strong>.
                        </p>

                        <div class="reports-grid">
                            <div class="report-card">
                                <div class="report-icon"><i class="bi bi-cart3"></i></div>
                                <h4>Rapport des ventes</h4>
                                <p>Chiffre d'affaires, nombre de ventes, panier moyen, évolution par période. Filtrez par date, produit ou client.</p>
                            </div>
                            <div class="report-card">
                                <div class="report-icon"><i class="bi bi-people"></i></div>
                                <h4>Rapport clients</h4>
                                <p>Meilleurs clients, fréquence d'achat, valeur vie client. Identifiez vos clients les plus rentables.</p>
                            </div>
                            <div class="report-card">
                                <div class="report-icon"><i class="bi bi-box-seam"></i></div>
                                <h4>Rapport produits</h4>
                                <p>Produits les plus vendus, marges par produit, rotation des stocks. Détectez les produits dormants.</p>
                            </div>
                            <div class="report-card">
                                <div class="report-icon"><i class="bi bi-clipboard-data"></i></div>
                                <h4>Inventaire</h4>
                                <p>Valeur totale du stock, produits en rupture, produits en surstock. Exportable en PDF ou Excel.</p>
                            </div>
                            <div class="report-card">
                                <div class="report-icon"><i class="bi bi-grid-3x3"></i></div>
                                <h4>Stocks groupés</h4>
                                <p>Vue consolidée du stock par catégorie ou fournisseur. Parfait pour les inventaires périodiques.</p>
                            </div>
                            <div class="report-card">
                                <div class="report-icon"><i class="bi bi-file-earmark-pdf"></i></div>
                                <h4>Factures</h4>
                                <p>Retrouvez et téléchargez toutes vos factures émises. Filtrez par client, date ou statut de paiement.</p>
                            </div>
                        </div>

                        <div class="info-box info-tip" style="margin-top: 24px;">
                            <i class="bi bi-lightbulb-fill"></i>
                            <div><strong>Export des rapports :</strong> La plupart des rapports sont exportables en format CSV ou PDF via le bouton <strong>Exporter</strong> en haut à droite de chaque rapport.</div>
                        </div>
                    </section>

                    <div class="guide-divider"></div>

                    {{-- ============================
                         10. ÉQUIPE & RÔLES
                    ============================ --}}
                    <section class="guide-section" id="utilisateurs">
                        <div class="section-badge-guide">
                            <i class="bi bi-person-gear"></i> Équipe & rôles
                        </div>
                        <h2>Gérer votre équipe et les droits d'accès</h2>
                        <p class="section-intro">
                            Invitez vos collaborateurs (caissiers, magasiniers, gérants) et définissez précisément ce qu'ils peuvent faire dans l'application.
                        </p>

                        <div class="roles-grid">
                            <div class="role-card role-admin">
                                <div class="role-badge">Super Admin</div>
                                <h4>Propriétaire</h4>
                                <p>Accès total à toutes les fonctionnalités, gestion des utilisateurs, facturation et abonnement.</p>
                                <ul class="role-permissions">
                                    <li><i class="bi bi-check-circle-fill text-success"></i> Toutes les fonctionnalités</li>
                                    <li><i class="bi bi-check-circle-fill text-success"></i> Gestion des utilisateurs</li>
                                    <li><i class="bi bi-check-circle-fill text-success"></i> Rapports complets</li>
                                    <li><i class="bi bi-check-circle-fill text-success"></i> Paramètres & abonnement</li>
                                </ul>
                            </div>
                            <div class="role-card role-manager">
                                <div class="role-badge">Admin</div>
                                <h4>Gérant / Responsable</h4>
                                <p>Gestion complète du stock, des ventes et des rapports. Ne peut pas gérer les utilisateurs.</p>
                                <ul class="role-permissions">
                                    <li><i class="bi bi-check-circle-fill text-success"></i> Produits, ventes, clients</li>
                                    <li><i class="bi bi-check-circle-fill text-success"></i> Fournisseurs</li>
                                    <li><i class="bi bi-check-circle-fill text-success"></i> Rapports</li>
                                    <li><i class="bi bi-x-circle-fill text-danger"></i> Gestion des utilisateurs</li>
                                </ul>
                            </div>
                            <div class="role-card role-cashier">
                                <div class="role-badge">Utilisateur</div>
                                <h4>Caissier / Vendeur</h4>
                                <p>Peut créer des ventes et consulter les produits. Accès limité aux fonctions de base.</p>
                                <ul class="role-permissions">
                                    <li><i class="bi bi-check-circle-fill text-success"></i> Créer des ventes</li>
                                    <li><i class="bi bi-check-circle-fill text-success"></i> Consulter les produits</li>
                                    <li><i class="bi bi-x-circle-fill text-danger"></i> Modifier les produits</li>
                                    <li><i class="bi bi-x-circle-fill text-danger"></i> Voir les rapports</li>
                                </ul>
                            </div>
                        </div>

                        <div class="guide-feature-block" style="margin-top: 32px;">
                            <div class="feature-block-icon"><i class="bi bi-person-plus-fill"></i></div>
                            <div class="feature-block-body">
                                <h3>Inviter un utilisateur</h3>
                                <ol class="guide-steps-list">
                                    <li>Allez dans <strong>Utilisateurs → Nouvel utilisateur</strong></li>
                                    <li>Renseignez le nom, l'email et choisissez le rôle</li>
                                    <li>Cliquez sur <strong>Créer</strong></li>
                                    <li>L'utilisateur reçoit ses identifiants par email</li>
                                </ol>
                            </div>
                        </div>
                    </section>

                    <div class="guide-divider"></div>

                    {{-- ============================
                         11. ABONNEMENT
                    ============================ --}}
                    <section class="guide-section" id="abonnement">
                        <div class="section-badge-guide">
                            <i class="bi bi-credit-card"></i> Abonnement
                        </div>
                        <h2>Gérer votre abonnement</h2>

                        <div class="guide-feature-block">
                            <div class="feature-block-icon"><i class="bi bi-eye"></i></div>
                            <div class="feature-block-body">
                                <h3>Voir mon abonnement</h3>
                                <p>Cliquez sur votre nom en haut à droite → <strong>Abonnement</strong>. Vous y trouvez :</p>
                                <ul class="guide-list">
                                    <li>La formule active et sa date d'expiration</li>
                                    <li>Le statut de paiement (actif, essai, expiré)</li>
                                    <li>L'historique des paiements</li>
                                </ul>
                            </div>
                        </div>

                        <div class="guide-feature-block">
                            <div class="feature-block-icon"><i class="bi bi-arrow-repeat"></i></div>
                            <div class="feature-block-body">
                                <h3>Renouveler ou changer de formule</h3>
                                <p>Depuis la page abonnement, cliquez sur <strong>Renouveler / Changer de formule</strong>. Choisissez la nouvelle formule et procédez au paiement via Wave, Orange Money ou carte bancaire.</p>
                                <div class="plans-summary">
                                    <div class="plan-row">
                                        <span class="plan-name">Mensuel</span>
                                        <span class="plan-price">10 000 FCFA / mois</span>
                                    </div>
                                    <div class="plan-row">
                                        <span class="plan-name">Trimestriel</span>
                                        <span class="plan-price">28 500 FCFA <span class="plan-saving">-5%</span></span>
                                    </div>
                                    <div class="plan-row popular-plan">
                                        <span class="plan-name">Semestriel ⭐</span>
                                        <span class="plan-price">54 000 FCFA <span class="plan-saving">-10%</span></span>
                                    </div>
                                    <div class="plan-row">
                                        <span class="plan-name">Annuel</span>
                                        <span class="plan-price">85 000 FCFA <span class="plan-saving">-29%</span></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="guide-feature-block">
                            <div class="feature-block-icon"><i class="bi bi-x-circle"></i></div>
                            <div class="feature-block-body">
                                <h3>Résilier</h3>
                                <p>Vous pouvez résilier à tout moment sans frais depuis la page <strong>Abonnement → Résilier mon abonnement</strong>. Votre accès reste actif jusqu'à la fin de la période payée.</p>
                            </div>
                        </div>
                    </section>

                    <div class="guide-divider"></div>

                    {{-- ============================
                         12. FAQ GUIDE
                    ============================ --}}
                    <section class="guide-section" id="faq-guide">
                        <div class="section-badge-guide">
                            <i class="bi bi-question-circle"></i> Questions fréquentes
                        </div>
                        <h2>Questions fréquentes</h2>

                        <div class="faq-accordion">
                            <div class="faq-item-guide">
                                <button class="faq-question" onclick="toggleFaq(this)">
                                    <span>Puis-je utiliser Sellvantix sur mobile ?</span>
                                    <i class="bi bi-chevron-down"></i>
                                </button>
                                <div class="faq-answer">
                                    <p>Oui, Sellvantix est entièrement responsive. Il fonctionne sur smartphones et tablettes via votre navigateur web. Aucune application à télécharger.</p>
                                </div>
                            </div>

                            <div class="faq-item-guide">
                                <button class="faq-question" onclick="toggleFaq(this)">
                                    <span>Mes données sont-elles sauvegardées ?</span>
                                    <i class="bi bi-chevron-down"></i>
                                </button>
                                <div class="faq-answer">
                                    <p>Oui. Des sauvegardes automatiques sont effectuées chaque nuit. Vos données sont chiffrées et hébergées sur des serveurs sécurisés. Vous ne risquez aucune perte en cas de panne de votre appareil.</p>
                                </div>
                            </div>

                            <div class="faq-item-guide">
                                <button class="faq-question" onclick="toggleFaq(this)">
                                    <span>Que se passe-t-il si mon abonnement expire ?</span>
                                    <i class="bi bi-chevron-down"></i>
                                </button>
                                <div class="faq-answer">
                                    <p>En cas d'expiration, votre accès à l'application est suspendu mais vos données sont conservées pendant 30 jours. Il vous suffit de renouveler votre abonnement pour retrouver toutes vos données intactes.</p>
                                </div>
                            </div>

                            <div class="faq-item-guide">
                                <button class="faq-question" onclick="toggleFaq(this)">
                                    <span>Combien d'utilisateurs puis-je ajouter ?</span>
                                    <i class="bi bi-chevron-down"></i>
                                </button>
                                <div class="faq-answer">
                                    <p>L'abonnement Sellvantix inclut un nombre d'utilisateurs généreux adapté aux petites et moyennes structures. Vous pouvez créer autant d'utilisateurs que nécessaire pour votre équipe.</p>
                                </div>
                            </div>

                            <div class="faq-item-guide">
                                <button class="faq-question" onclick="toggleFaq(this)">
                                    <span>Comment exporter mes données ?</span>
                                    <i class="bi bi-chevron-down"></i>
                                </button>
                                <div class="faq-answer">
                                    <p>La plupart des listes (produits, clients, ventes, rapports) proposent un bouton <strong>Exporter</strong> en haut de page. Les exports sont disponibles en format CSV, Excel ou PDF selon le module.</p>
                                </div>
                            </div>

                            <div class="faq-item-guide">
                                <button class="faq-question" onclick="toggleFaq(this)">
                                    <span>Comment contacter le support ?</span>
                                    <i class="bi bi-chevron-down"></i>
                                </button>
                                <div class="faq-answer">
                                    <p>Notre équipe support est disponible par email et chat. Vous pouvez aussi consulter la <a href="{{ route('faq') }}">FAQ complète</a> pour trouver des réponses instantanées. Délai de réponse habituel : moins de 24h.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- CTA FINAL --}}
                    <div class="guide-cta-block">
                        <div class="guide-cta-inner">
                            <h3>Prêt à démarrer ?</h3>
                            <p>Essayez Sellvantix gratuitement pendant 14 jours — sans carte bancaire, sans engagement.</p>
                            <div class="guide-cta-actions">
                                <a href="{{ route('demo') }}" class="btn-guide-outline">
                                    <i class="bi bi-play-circle"></i> Voir la démo
                                </a>
                                <a href="{{ route('pricing') }}" class="btn-guide-primary">
                                    <i class="bi bi-rocket-takeoff"></i> Commencer gratuitement
                                </a>
                            </div>
                        </div>
                    </div>

                </main>
            </div>
        </div>
    </section>
</div>

<style>
/* =====================================================
   GUIDE PAGE STYLES
===================================================== */

.guide-page {
    min-height: 100vh;
}

/* Hero */
.guide-hero {
    background: linear-gradient(135deg, var(--orange-50) 0%, var(--white) 100%);
    padding: 64px 0 48px;
    border-bottom: 1px solid var(--border);
}

.guide-hero-content {
    max-width: 720px;
}

.hero-badge {
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

.hero-title {
    font-size: 42px;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 16px;
    color: var(--gray-900);
}

.text-gradient {
    background: linear-gradient(135deg, var(--orange-500), var(--orange-600));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.hero-subtitle {
    font-size: 17px;
    color: var(--gray-600);
    margin-bottom: 28px;
    line-height: 1.6;
}

.guide-search {
    display: flex;
    align-items: center;
    gap: 12px;
    background: var(--white);
    border: 1.5px solid var(--border);
    border-radius: var(--radius-full);
    padding: 12px 20px;
    max-width: 520px;
    box-shadow: var(--shadow-sm);
    transition: border-color 0.2s, box-shadow 0.2s;
}

.guide-search:focus-within {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
}

.guide-search i {
    color: var(--gray-400);
    font-size: 18px;
    flex-shrink: 0;
}

.guide-search input {
    border: none;
    outline: none;
    background: transparent;
    font-size: 14px;
    color: var(--gray-700);
    width: 100%;
    font-family: 'Inter', sans-serif;
}

.guide-search input::placeholder {
    color: var(--gray-400);
}

/* Body layout */
.guide-body {
    padding: 48px 0 80px;
}

.guide-layout {
    display: grid;
    grid-template-columns: 260px 1fr;
    gap: 48px;
    align-items: start;
}

/* Sidebar */
.guide-sidebar {
    position: sticky;
    top: 88px;
}

.sidebar-sticky {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 24px;
}

.sidebar-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--gray-400);
    margin-bottom: 16px;
}

.sidebar-nav {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.sidebar-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 500;
    color: var(--gray-600);
    transition: all 0.2s;
    text-decoration: none;
}

.sidebar-link i {
    font-size: 15px;
    width: 18px;
    flex-shrink: 0;
}

.sidebar-link:hover,
.sidebar-link.active {
    background: var(--orange-50);
    color: var(--orange-600);
}

.sidebar-cta {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid var(--border);
    text-align: center;
}

.sidebar-cta p {
    font-size: 13px;
    color: var(--gray-500);
    margin-bottom: 10px;
}

.btn-sidebar-help {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background: var(--orange-50);
    color: var(--orange-600);
    border-radius: var(--radius-full);
    font-size: 13px;
    font-weight: 600;
    transition: all 0.2s;
    text-decoration: none;
    border: 1px solid var(--orange-200);
}

.btn-sidebar-help:hover {
    background: var(--orange-500);
    color: var(--white);
    border-color: var(--orange-500);
}

/* Guide content */
.guide-content {
    min-width: 0;
}

.guide-section {
    margin-bottom: 8px;
    scroll-margin-top: 100px;
}

.section-badge-guide {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 14px;
    background: var(--orange-50);
    border-radius: 30px;
    font-size: 13px;
    font-weight: 600;
    color: var(--orange-600);
    margin-bottom: 14px;
    border: 1px solid var(--orange-100);
}

.guide-section h2 {
    font-size: 28px;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 12px;
}

.section-intro {
    font-size: 16px;
    color: var(--gray-600);
    line-height: 1.6;
    margin-bottom: 28px;
}

.guide-divider {
    height: 1px;
    background: var(--border);
    margin: 40px 0;
}

/* Steps grid */
.steps-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}

.step-card {
    display: flex;
    gap: 16px;
    align-items: flex-start;
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 20px;
    transition: all 0.2s;
}

.step-card:hover {
    border-color: var(--orange-300);
    box-shadow: var(--shadow-md);
}

.step-number {
    width: 36px;
    height: 36px;
    background: var(--accent-gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-weight: 800;
    font-size: 16px;
    flex-shrink: 0;
}

.step-body h4 {
    font-size: 15px;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 6px;
}

.step-body p {
    font-size: 14px;
    color: var(--gray-600);
    line-height: 1.5;
}

.step-body a {
    color: var(--accent);
    font-weight: 500;
}

/* Info boxes */
.info-box {
    display: flex;
    gap: 14px;
    padding: 16px 20px;
    border-radius: 12px;
    margin-top: 16px;
    font-size: 14px;
    line-height: 1.5;
}

.info-box i {
    font-size: 20px;
    flex-shrink: 0;
    margin-top: 1px;
}

.info-box p {
    margin-top: 4px;
    color: inherit;
    opacity: 0.85;
}

.info-success {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #15803d;
}

.info-tip {
    background: var(--orange-50);
    border: 1px solid var(--orange-200);
    color: var(--orange-700);
}

.info-warning {
    background: #fffbeb;
    border: 1px solid #fde68a;
    color: #92400e;
}

/* Feature blocks */
.guide-feature-block {
    display: flex;
    gap: 20px;
    margin-bottom: 28px;
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 24px;
    transition: border-color 0.2s;
}

.guide-feature-block:hover {
    border-color: var(--orange-200);
}

.feature-block-icon {
    width: 48px;
    height: 48px;
    background: var(--orange-50);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--orange-500);
    font-size: 22px;
    flex-shrink: 0;
}

.feature-block-body {
    flex: 1;
    min-width: 0;
}

.feature-block-body h3 {
    font-size: 17px;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 8px;
}

.feature-block-body p {
    font-size: 14px;
    color: var(--gray-600);
    line-height: 1.6;
    margin-bottom: 12px;
}

.feature-block-body p:last-child {
    margin-bottom: 0;
}

/* Steps list */
.guide-steps-list {
    list-style: none;
    counter-reset: steps;
    margin: 8px 0;
}

.guide-steps-list li {
    counter-increment: steps;
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 6px 0;
    font-size: 14px;
    color: var(--gray-700);
    line-height: 1.5;
}

.guide-steps-list li::before {
    content: counter(steps);
    width: 22px;
    height: 22px;
    background: var(--orange-100);
    color: var(--orange-700);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
    flex-shrink: 0;
    margin-top: 1px;
}

.guide-steps-list strong {
    color: var(--gray-900);
}

/* Generic list */
.guide-list {
    list-style: none;
    margin: 8px 0;
}

.guide-list li {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    padding: 5px 0;
    font-size: 14px;
    color: var(--gray-600);
    line-height: 1.5;
}

.guide-list li::before {
    content: '—';
    color: var(--orange-400);
    flex-shrink: 0;
}

.guide-list strong {
    color: var(--gray-800);
}

/* Guide cards grid */
.guide-cards-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
    margin-top: 20px;
}

.guide-info-card {
    background: var(--gray-50);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 20px;
}

.guide-info-icon {
    width: 40px;
    height: 40px;
    background: var(--orange-50);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--orange-500);
    font-size: 18px;
    margin-bottom: 12px;
}

.guide-info-card h4 {
    font-size: 14px;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 6px;
}

.guide-info-card p {
    font-size: 13px;
    color: var(--gray-600);
    line-height: 1.5;
}

/* Dashboard preview */
.dashboard-preview-guide {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 24px;
}

.preview-row-guide {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
}

.preview-stat-card {
    background: var(--gray-50);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 16px;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
}

.preview-stat-card i {
    font-size: 24px;
    color: var(--orange-500);
    margin-bottom: 4px;
}

.preview-stat-label {
    font-size: 12px;
    color: var(--gray-500);
    font-weight: 500;
}

.preview-stat-value {
    font-size: 18px;
    font-weight: 800;
    color: var(--gray-900);
}

.preview-stat-alert i {
    color: #f59e0b;
}

.preview-stat-alert .preview-stat-value {
    color: #f59e0b;
}

/* Reports grid */
.reports-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}

.report-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 20px;
    transition: all 0.2s;
}

.report-card:hover {
    border-color: var(--orange-300);
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.report-icon {
    width: 44px;
    height: 44px;
    background: var(--orange-50);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--orange-500);
    font-size: 20px;
    margin-bottom: 14px;
}

.report-card h4 {
    font-size: 15px;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 6px;
}

.report-card p {
    font-size: 13px;
    color: var(--gray-600);
    line-height: 1.5;
}

/* Roles grid */
.roles-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}

.role-card {
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 22px;
    background: var(--white);
}

.role-badge {
    display: inline-block;
    padding: 3px 12px;
    border-radius: 30px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 12px;
}

.role-admin .role-badge {
    background: #fef3c7;
    color: #92400e;
}

.role-manager .role-badge {
    background: var(--orange-50);
    color: var(--orange-700);
}

.role-cashier .role-badge {
    background: var(--gray-100);
    color: var(--gray-600);
}

.role-card h4 {
    font-size: 15px;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 8px;
}

.role-card p {
    font-size: 13px;
    color: var(--gray-600);
    line-height: 1.5;
    margin-bottom: 14px;
}

.role-permissions {
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.role-permissions li {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: var(--gray-700);
}

.text-success { color: #10b981; }
.text-danger { color: #ef4444; }

/* Plans summary */
.plans-summary {
    margin-top: 16px;
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
}

.plan-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 16px;
    font-size: 14px;
    border-bottom: 1px solid var(--border);
}

.plan-row:last-child {
    border-bottom: none;
}

.popular-plan {
    background: var(--orange-50);
}

.plan-name {
    font-weight: 600;
    color: var(--gray-800);
}

.plan-price {
    color: var(--gray-700);
    font-weight: 500;
}

.plan-saving {
    display: inline-block;
    padding: 2px 8px;
    background: var(--orange-100);
    color: var(--orange-700);
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    margin-left: 8px;
}

/* FAQ Accordion */
.faq-accordion {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.faq-item-guide {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
    transition: border-color 0.2s;
}

.faq-item-guide:has(.faq-answer.open) {
    border-color: var(--orange-300);
}

.faq-question {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px 20px;
    background: transparent;
    border: none;
    cursor: pointer;
    text-align: left;
    font-size: 15px;
    font-weight: 600;
    color: var(--gray-900);
    font-family: 'Inter', sans-serif;
    gap: 16px;
}

.faq-question i {
    font-size: 16px;
    color: var(--gray-400);
    transition: transform 0.2s;
    flex-shrink: 0;
}

.faq-question.open i {
    transform: rotate(180deg);
    color: var(--orange-500);
}

.faq-answer {
    display: none;
    padding: 0 20px 18px;
    font-size: 14px;
    color: var(--gray-600);
    line-height: 1.6;
}

.faq-answer.open {
    display: block;
}

.faq-answer a {
    color: var(--accent);
    font-weight: 500;
}

/* CTA block */
.guide-cta-block {
    margin-top: 48px;
    background: var(--gray-900);
    border-radius: 24px;
    padding: 48px;
    text-align: center;
    color: var(--white);
}

.guide-cta-block h3 {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 12px;
}

.guide-cta-block p {
    font-size: 16px;
    opacity: 0.85;
    margin-bottom: 28px;
}

.guide-cta-actions {
    display: flex;
    gap: 14px;
    justify-content: center;
}

.btn-guide-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 28px;
    background: var(--white);
    color: var(--orange-600);
    border-radius: var(--radius-full);
    font-size: 15px;
    font-weight: 700;
    transition: all 0.2s;
    text-decoration: none;
}

.btn-guide-primary:hover {
    background: var(--orange-50);
    transform: translateY(-2px);
}

.btn-guide-outline {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 28px;
    background: transparent;
    color: var(--white);
    border: 1.5px solid rgba(255,255,255,0.4);
    border-radius: var(--radius-full);
    font-size: 15px;
    font-weight: 600;
    transition: all 0.2s;
    text-decoration: none;
}

.btn-guide-outline:hover {
    background: rgba(255,255,255,0.1);
    border-color: var(--white);
}

/* =====================================================
   RESPONSIVE
===================================================== */
@media (max-width: 1024px) {
    .guide-layout {
        grid-template-columns: 1fr;
    }

    .guide-sidebar {
        position: static;
        display: none; /* Caché sur mobile, accessible via bouton */
    }

    .steps-grid {
        grid-template-columns: 1fr;
    }

    .guide-cards-grid {
        grid-template-columns: 1fr;
    }

    .reports-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .roles-grid {
        grid-template-columns: 1fr;
    }

    .preview-row-guide {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 30px;
    }

    .guide-feature-block {
        flex-direction: column;
        gap: 14px;
    }

    .reports-grid {
        grid-template-columns: 1fr;
    }

    .guide-cta-block {
        padding: 32px 24px;
    }

    .guide-cta-actions {
        flex-direction: column;
        align-items: center;
    }

    .guide-cta-block h3 {
        font-size: 22px;
    }
}
</style>

<script>
// ---- FAQ Accordion ----
function toggleFaq(btn) {
    const answer = btn.nextElementSibling;
    const isOpen = answer.classList.contains('open');

    // Fermer tous les autres
    document.querySelectorAll('.faq-answer').forEach(a => a.classList.remove('open'));
    document.querySelectorAll('.faq-question').forEach(b => b.classList.remove('open'));

    if (!isOpen) {
        answer.classList.add('open');
        btn.classList.add('open');
    }
}

// ---- Sidebar active link on scroll ----
const sections = document.querySelectorAll('.guide-section');
const sidebarLinks = document.querySelectorAll('.sidebar-link');

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const id = entry.target.getAttribute('id');
            sidebarLinks.forEach(link => {
                link.classList.toggle('active', link.getAttribute('href') === '#' + id);
            });
        }
    });
}, { rootMargin: '-20% 0px -70% 0px' });

sections.forEach(section => observer.observe(section));

// ---- Recherche simple ----
const searchInput = document.getElementById('guide-search-input');
const allSections = document.querySelectorAll('.guide-section');

searchInput.addEventListener('input', function() {
    const query = this.value.toLowerCase().trim();

    if (!query) {
        allSections.forEach(s => s.style.display = '');
        document.querySelectorAll('.guide-divider').forEach(d => d.style.display = '');
        return;
    }

    allSections.forEach(section => {
        const text = section.textContent.toLowerCase();
        section.style.display = text.includes(query) ? '' : 'none';
    });

    document.querySelectorAll('.guide-divider').forEach(d => d.style.display = 'none');
});
</script>
@endsection
