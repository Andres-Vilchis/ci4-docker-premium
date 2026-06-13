<?php

namespace App\Services;

use Config\Database;
use Throwable;

class DatabaseService
{
    public static function check(): array
{
    $start = microtime(true);

    try {
        if (function_exists('auth') && auth()->user()) {
            // ensures tenant context is valid if present
            $tenant = TenantContextService::get();

            if ($tenant && !TenantContextService::hasTenant()) {
                throw new \RuntimeException('Invalid tenant context');
            }
        }

        $db = \Config\Database::connect();
        $db->query('SELECT 1');

        return [
            'status' => 'ok',
            'ms' => (microtime(true) - $start) * 1000,
        ];
    } catch (\Throwable $e) {
        return [
            'status' => 'fail',
            'ms' => (microtime(true) - $start) * 1000,
            'error' => $e->getMessage(),
        ];
    }
}
}
