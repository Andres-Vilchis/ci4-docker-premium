<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Queue extends BaseConfig
{

    public string $default = 'redis';

    public array $redis = [
        'host' => 'redis',
        'port' => 6379,
        'timeout' => 2.0,
    ];

    public int $retryAttempts = 3;

    public int $retryDelaySeconds = 5;

}