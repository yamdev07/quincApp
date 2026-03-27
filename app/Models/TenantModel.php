<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Classe de base pour tous les modèles qui utilisent la connexion tenant (base de données dynamique)
 * 
 * Cette classe permet de :
 * - Gérer automatiquement la connexion à la base de données du tenant
 * - Appliquer les scopes de tenant si nécessaire
 * - Centraliser la configuration pour tous les modèles tenant
 */
class TenantModel extends Model
{
    /**
     * La connexion à utiliser pour ce modèle
     * Les modèles enfants hériteront de cette connexion
     */
    protected $connection = 'tenant';

    /**
     * Indique si le modèle doit appliquer automatiquement les scopes de tenant
     */
    protected $applyTenantScope = false;

    /**
     * Le nom de la colonne tenant_id dans la table
     */
    protected $tenantColumn = 'tenant_id';

    /**
     * Boot du modèle
     */
    protected static function boot()
    {
        parent::boot();

        // Appliquer le scope de tenant si nécessaire
        if (static::$applyTenantScope) {
            static::addGlobalScope('tenant', function ($query) {
                $query->where(static::$tenantColumn, session('tenant_id'));
            });
        }
    }

    /**
     * Définir la connexion de base de données dynamiquement
     * 
     * @param string $database Nom de la base de données
     * @return $this
     */
    public function setDatabase($database)
    {
        // Changer la base de données pour cette connexion
        $config = config('database.connections.tenant');
        $config['database'] = $database;
        
        config(['database.connections.tenant_dynamic' => $config]);
        $this->connection = 'tenant_dynamic';
        
        return $this;
    }

    /**
     * Vérifier si la table existe dans la base de données du tenant
     * 
     * @return bool
     */
    public function tableExists(): bool
    {
        return Schema::connection($this->getConnectionName())
            ->hasTable($this->getTable());
    }

    /**
     * Exécuter une requête brute sur la connexion tenant
     * 
     * @param string $sql
     * @param array $bindings
     * @return mixed
     */
    public static function rawQuery($sql, array $bindings = [])
    {
        return DB::connection('tenant')->select($sql, $bindings);
    }

    /**
     * Compter le nombre de tables dans la base de données tenant
     * 
     * @return int
     */
    public static function getTableCount(): int
    {
        $connection = DB::connection('tenant');
        $database = $connection->getDatabaseName();
        
        // Requête compatible PostgreSQL
        $result = $connection->select("
            SELECT COUNT(*) as count 
            FROM information_schema.tables 
            WHERE table_catalog = ? 
            AND table_schema = 'public'
            AND table_type = 'BASE TABLE'
        ", [$database]);
        
        return $result[0]->count ?? 0;
    }

    /**
     * Récupérer la liste des tables dans la base de données tenant
     * 
     * @return array
     */
    public static function getTables(): array
    {
        $connection = DB::connection('tenant');
        $database = $connection->getDatabaseName();
        
        // Requête compatible PostgreSQL
        $tables = $connection->select("
            SELECT table_name 
            FROM information_schema.tables 
            WHERE table_catalog = ? 
            AND table_schema = 'public'
            AND table_type = 'BASE TABLE'
            ORDER BY table_name
        ", [$database]);
        
        return array_map(function($table) {
            return $table->table_name;
        }, $tables);
    }

    /**
     * Vérifier si la base de données tenant a été initialisée
     * 
     * @return bool
     */
    public static function isInitialized(): bool
    {
        try {
            // Vérifier si la table migrations existe
            return Schema::connection('tenant')->hasTable('migrations');
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Récupérer les statistiques de la base de données tenant
     * 
     * @return array
     */
    public static function getDatabaseStats(): array
    {
        try {
            $connection = DB::connection('tenant');
            $database = $connection->getDatabaseName();
            
            // Statistiques PostgreSQL
            $stats = $connection->select("
                SELECT 
                    pg_database_size(?) as size_bytes,
                    (SELECT COUNT(*) FROM information_schema.tables 
                     WHERE table_catalog = ? 
                     AND table_schema = 'public' 
                     AND table_type = 'BASE TABLE') as table_count,
                    (SELECT COUNT(*) FROM information_schema.columns 
                     WHERE table_catalog = ?) as column_count
            ", [$database, $database, $database]);
            
            $sizeBytes = $stats[0]->size_bytes ?? 0;
            
            return [
                'size_bytes' => $sizeBytes,
                'size_mb' => round($sizeBytes / 1024 / 1024, 2),
                'size_gb' => round($sizeBytes / 1024 / 1024 / 1024, 2),
                'table_count' => $stats[0]->table_count ?? 0,
                'column_count' => $stats[0]->column_count ?? 0,
                'is_initialized' => self::isInitialized(),
                'database_name' => $database,
            ];
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
                'is_initialized' => false,
            ];
        }
    }

    /**
     * Exécuter une requête SQL et retourner les résultats
     * 
     * @param string $sql
     * @param array $bindings
     * @return array
     */
    public static function executeQuery($sql, array $bindings = []): array
    {
        try {
            $results = DB::connection('tenant')->select($sql, $bindings);
            return [
                'success' => true,
                'data' => $results,
                'count' => count($results),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'data' => [],
            ];
        }
    }

    /**
     * Vérifier la connexion à la base de données tenant
     * 
     * @return array
     */
    public static function checkConnection(): array
    {
        try {
            DB::connection('tenant')->getPdo();
            return [
                'connected' => true,
                'database' => DB::connection('tenant')->getDatabaseName(),
                'host' => config('database.connections.tenant.host'),
                'port' => config('database.connections.tenant.port'),
            ];
        } catch (\Exception $e) {
            return [
                'connected' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Obtenir le nom de la base de données tenant
     * 
     * @return string|null
     */
    public static function getDatabaseName(): ?string
    {
        try {
            return DB::connection('tenant')->getDatabaseName();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Activer le scope de tenant pour ce modèle
     * 
     * @param bool $enabled
     * @return void
     */
    public static function enableTenantScope($enabled = true)
    {
        static::$applyTenantScope = $enabled;
    }

    /**
     * Définir la colonne tenant pour ce modèle
     * 
     * @param string $column
     * @return void
     */
    public static function setTenantColumn($column)
    {
        static::$tenantColumn = $column;
    }

    /**
     * Scope pour filtrer par tenant
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $tenantId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByTenant($query, $tenantId)
    {
        return $query->where($this->tenantColumn, $tenantId);
    }

    /**
     * Scope pour exclure un tenant
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $tenantId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotByTenant($query, $tenantId)
    {
        return $query->where($this->tenantColumn, '!=', $tenantId);
    }
}