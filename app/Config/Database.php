<?php

namespace Config;

use CodeIgniter\Database\Config;

class Database extends Config
{
    public string $filesPath = APPPATH . 'Database' . DIRECTORY_SEPARATOR;

    public string $defaultGroup = 'default';

    /**
     * BASE CONNECTION (DINAMIC)
     */
    public array $default = [
        'DSN'          => '',
        'hostname'     => '',
        'username'     => '',
        'password'     => '',
        'database'     => '',
        'DBDriver'     => 'MySQLi',
        'DBPrefix'     => '',
        'pConnect'     => false,
        'DBDebug'      => false,
        'charset'      => 'utf8mb4',
        'DBCollat'     => 'utf8mb4_general_ci',
        'swapPre'      => '',
        'encrypt'      => false,
        'compress'     => false,
        'strictOn'     => false,
        'failover'     => [],
        'port'         => 3306,
        'numberNative' => false,
        'foundRows'    => false,
        'dateFormat'   => [
            'date'     => 'Y-m-d',
            'datetime' => 'Y-m-d H:i:s',
            'time'     => 'H:i:s',
        ],
    ];

    /**
     * TEST ENV (SAFE)
     */
    public array $tests = [
        'DSN'         => '',
        'hostname'    => '127.0.0.1',
        'username'    => '',
        'password'    => '',
        'database'    => ':memory:',
        'DBDriver'    => 'SQLite3',
        'DBPrefix'    => 'db_',
        'pConnect'    => false,
        'DBDebug'     => true,
        'charset'     => 'utf8',
        'DBCollat'    => '',
        'swapPre'     => '',
        'encrypt'     => false,
        'compress'    => false,
        'strictOn'    => true,
        'failover'    => [],
        'port'        => 3306,
        'foreignKeys' => true,
        'busyTimeout' => 1000,
        'synchronous' => null,
        'dateFormat'  => [
            'date'     => 'Y-m-d',
            'datetime' => 'Y-m-d H:i:s',
            'time'     => 'H:i:s',
        ],
    ];

    public function __construct()
    {
        parent::__construct();

        /**
         * 🔥 ENVIRONMENT SWITCH CORE (PRODUCT FEATURE)
         */

        $this->default['hostname'] = env('database.default.hostname', $this->detectHost());
        $this->default['database'] = env('database.default.database', 'ci4_app');
        $this->default['username'] = env('database.default.username', 'ci4');
        $this->default['password'] = env('database.default.password', 'secret');
        $this->default['port']     = (int) env('database.default.port', 3306);
        $this->default['DBDriver'] = env('database.default.DBDriver', 'MySQLi');

        /**
         * DEBUG MODE CONTROLLED BY ENV
         */
        $this->default['DBDebug'] = filter_var(
            env('database.default.DBDebug', false),
            FILTER_VALIDATE_BOOLEAN
        );

        /**
         * AUTO SWITCH TEST ENVIRONMENT
         */
        if (ENVIRONMENT === 'testing') {
            $this->defaultGroup = 'tests';
        }
    }

    /**
     * 🔥 SMART DOCKER / LOCAL DETECTION
     */
    private function detectHost(): string
    {
        // Docker service name (docker-compose)
        if (getenv('DB_HOST')) {
            return getenv('DB_HOST');
        }

        // Railway / Render / VPS env override
        if (getenv('DATABASE_HOST')) {
            return getenv('DATABASE_HOST');
        }

        // Default Docker Compose service name
        return 'mysql';
    }
}