<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Vérification quotidienne des abonnements expirés (2h du matin)
Schedule::command('subscriptions:check')->dailyAt('02:00');

// Nettoyage cache vues chaque semaine (dimanche)
Schedule::command('view:clear')->weekly();
