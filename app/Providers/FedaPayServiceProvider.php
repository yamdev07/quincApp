<?php
// app/Providers/FedaPayServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use FedaPay\FedaPay;

class FedaPayServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('fedapay', function ($app) {
            $config = config('services.fedapay');
            
            // Configuration de FedaPay
            FedaPay::setApiKey($config['secret_key']);
            FedaPay::setEnvironment($config['mode']);
            
            // Mode debug
            if ($config['debug']) {
                error_reporting(E_ALL);
                ini_set('display_errors', 1);
            }
            
            return new \stdClass();
        });
    }
    
    public function boot()
    {
        //
    }
}