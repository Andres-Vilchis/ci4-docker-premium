<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * FEATURE FLAGS SYSTEM (PRODUCT LAYER)
 *
 * This file controls behavior:
 * - Framework
 * - Product
 * - toggles
 */
class Feature extends BaseConfig
{
    /*
    |--------------------------------------------------------------------------
    | CI4 CORE FEATURES (COMPATIBILITY LAYER)
    |--------------------------------------------------------------------------
    */

    public bool $autoRoutesImproved = true;
    public bool $oldFilterOrder = false;
    public bool $limitZeroAsAll = true;
    public bool $strictLocaleNegotiation = false;

    /*
    |--------------------------------------------------------------------------
    | PRODUCT FEATURE FLAGS (STARTER KIT VALUE LAYER)
    |--------------------------------------------------------------------------
    */

    /**
     * Redis cache system
     */
    public bool $cacheEnabled = true;

    /**
     * Queue system (Daycry Queues)
     */
    public bool $queueEnabled = true;

    /**
     * Sentry error tracking
     */
    public bool $sentryEnabled = false;

    /**
     * Healthcheck endpoint (/health)
     */
    public bool $healthCheckEnabled = true;

    /**
     * Debug mode visual tools (toolbar, verbose logs)
     */
    public bool $debugMode = true;

    /**
     * API rate limiting system
     */
    public bool $rateLimitEnabled = false;

    /**
     * Maintenance mode override
     */
    public bool $maintenanceMode = false;

    /*
    |--------------------------------------------------------------------------
    | CONSTRUCTOR (ENV-DRIVEN SYSTEM)
    |--------------------------------------------------------------------------
    */

    public function __construct()
    {
        parent::__construct();

        /**
         * ENV OVERWRITE SYSTEM (CRITICAL FOR PRODUCT)
         */

        $this->cacheEnabled = filter_var(
            env('feature.cache_enabled', true),
            FILTER_VALIDATE_BOOLEAN
        );

        $this->queueEnabled = filter_var(
            env('feature.queue_enabled', true),
            FILTER_VALIDATE_BOOLEAN
        );

        $this->sentryEnabled = filter_var(
            env('feature.sentry_enabled', false),
            FILTER_VALIDATE_BOOLEAN
        );

        $this->healthCheckEnabled = filter_var(
            env('feature.healthcheck_enabled', true),
            FILTER_VALIDATE_BOOLEAN
        );

        $this->debugMode = filter_var(
            env('feature.debug_mode', true),
            FILTER_VALIDATE_BOOLEAN
        );

        $this->rateLimitEnabled = filter_var(
            env('feature.rate_limit_enabled', false),
            FILTER_VALIDATE_BOOLEAN
        );

        $this->maintenanceMode = filter_var(
            env('feature.maintenance_mode', false),
            FILTER_VALIDATE_BOOLEAN
        );
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS (OPTIONAL BUT POWERFUL)
    |--------------------------------------------------------------------------
    */

    public function isProductionSafe(): bool
    {
        return !$this->debugMode && !$this->maintenanceMode;
    }

    public function isObservabilityEnabled(): bool
    {
        return $this->sentryEnabled || $this->debugMode;
    }

    public function isQueueSystemActive(): bool
    {
        return $this->queueEnabled && !$this->maintenanceMode;
    }
}