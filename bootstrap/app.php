<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\SuperAdminGlobalMiddleware;
use App\Http\Middleware\StockManagerMiddleware;
use App\Http\Middleware\CheckTrial;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'super_admin_global' => SuperAdminGlobalMiddleware::class,
            'stock.manager' => StockManagerMiddleware::class,
            'check.trial' => CheckTrial::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Violation de contrainte unique → erreur de validation conviviale
        $exceptions->render(function (UniqueConstraintViolationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Cette valeur existe déjà.'], 422);
            }
            return back()->withErrors(['duplicate' => 'Cette valeur existe déjà dans le système.'])->withInput();
        });

        // Accès refusé → redirection avec message
        $exceptions->render(function (AuthorizationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Action non autorisée.'], 403);
            }
            return redirect()->back()->with('error', $e->getMessage() ?: 'Vous n\'avez pas les droits pour cette action.');
        });

        // Log toutes les 500 non gérées avec contexte utilisateur
        $exceptions->reportable(function (\Throwable $e) use ($exceptions): void {
            if (!($e instanceof AuthorizationException) && !($e instanceof UniqueConstraintViolationException)) {
                Log::error('Erreur non gérée', [
                    'exception' => get_class($e),
                    'message'   => $e->getMessage(),
                    'url'       => request()->fullUrl(),
                    'user_id'   => auth()->id(),
                    'tenant_id' => auth()->user()?->tenant_id,
                ]);
            }
        });
    })->create();