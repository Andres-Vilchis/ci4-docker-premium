<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class App extends BaseConfig
{
   
    public string $baseURL = '';

    public array $allowedHostnames = [];

    public string $indexPage = '';

    public string $uriProtocol = 'REQUEST_URI';

    public string $permittedURIChars = 'a-z 0-9~%.:_\-';

    public string $defaultLocale = 'en';

    public bool $negotiateLocale = false;

    public array $supportedLocales = ['en'];

    public string $appTimezone = 'UTC';

    public string $charset = 'UTF-8';

    public bool $forceGlobalSecureRequests = false;

    public array $proxyIPs = [];

    public bool $CSPEnabled = false;

    public function __construct()
    {
        parent::__construct();
        $this->baseURL = rtrim(
            env('app.baseURL', $this->detectBaseURL()),
            '/'
        ) . '/';

        $this->forceGlobalSecureRequests = filter_var(
            env('APP_FORCE_HTTPS', false),
            FILTER_VALIDATE_BOOLEAN
        );

        $this->CSPEnabled = filter_var(
            env('APP_CSP_ENABLED', false),
            FILTER_VALIDATE_BOOLEAN
        );
    }

    private function detectBaseURL(): string
    {
        // Docker / local default
        if (php_sapi_name() === 'cli') {
            return 'http://localhost/';
        }

        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            ? 'https'
            : 'http';

        return $scheme . '://' . $host . '/';
    }
}