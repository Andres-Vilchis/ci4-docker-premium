<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;
use CodeIgniter\Shield\Filters\AuthFilter;
use CodeIgniter\Shield\Filters\SessionAuth;

class Filters extends BaseFilters
{
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'invalidchars'  => InvalidChars::class,
        'forcehttps'    => ForceHTTPS::class,
        'pagecache'     => PageCache::class,
        'performance'   => PerformanceMetrics::class,
        'secureheaders' => SecureHeaders::class,
        'auth'          => AuthFilter::class,
        'session'       => SessionAuth::class,

        'tenant'        => \App\Filters\TenantFilter::class,
        'tenantRate'    => \App\Filters\TenantRateLimitFilter::class,
        'apiTenant'     => \App\Filters\ApiTenantAuthFilter::class,
        'observability' => \App\Filters\ObservabilityFilter::class,
    ];

    public array $required = [
        'before' => ['forcehttps', 'pagecache'],
        'after'  => ['pagecache', 'performance', 'toolbar'],
    ];

    public array $globals = [
        'before' => [
            'invalidchars',
            'tenant' => [
                'except' => [
                    'health',
                    'health/*',
                    'login',
                    'login/*',
                    'register',
                    'register/*',
                    'post-login',
                ],
            ],
            'observability',
        ],
        'after' => [
            'secureheaders',
        ],
    ];

    public array $methods = [];

    public array $filters = [
        'auth' => [
            'before' => ['admin/*'],
        ],
        'session' => [
            'before' => ['admin/*'],
        ],
        'tenantRate' => [
            'before' => ['*', '!health', '!health/*'],
        ],
        'apiTenant' => [
            'before' => ['api/*'],
        ],
    ];
}