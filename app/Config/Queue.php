<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Queue extends BaseConfig
{
    /**
     * Default queue driver
     */
    public string $default = 'redis';

    /**
     * Redis connection
     */
    public array $redis = [
        'host' => 'redis',
        'port' => 6379,
        'password' => null,
        'database' => 0,
        'timeout' => 2.0,
    ];

    /**
     * Queue behavior
     */
    public int $retryAttempts = 3;

    public int $retryDelaySeconds = 5;

    public bool $logJobs = true;

    public bool $trackFailures = true;

    /**
     * Redis keys prefix
     */
    public string $prefix = 'ci4_queue';
}