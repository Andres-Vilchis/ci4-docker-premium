<?php

namespace Config;

use CodeIgniter\Cache\CacheInterface;
use CodeIgniter\Cache\Handlers\ApcuHandler;
use CodeIgniter\Cache\Handlers\DummyHandler;
use CodeIgniter\Cache\Handlers\FileHandler;
use CodeIgniter\Cache\Handlers\MemcachedHandler;
use CodeIgniter\Cache\Handlers\PredisHandler;
use CodeIgniter\Cache\Handlers\RedisHandler;
use CodeIgniter\Cache\Handlers\WincacheHandler;
use CodeIgniter\Config\BaseConfig;

class Cache extends BaseConfig
{
    public string $handler = 'redis';

    public string $backupHandler = 'dummy';

    public string $prefix = 'ci4_';

    public int $ttl = 60;

    public string $reservedCharacters = '{}()/\@:';

    public array $file = [
        'storePath' => WRITEPATH . 'cache/',
        'mode'      => 0640,
    ];

    public array $memcached = [
        'host'   => '127.0.0.1',
        'port'   => 11211,
        'weight' => 1,
        'raw'    => false,
    ];

    public array $redis = [
        'host'       => '',
        'password'   => null,
        'port'       => 6379,
        'timeout'    => 2.0,
        'async'      => false,
        'persistent' => false,
        'database'   => 0,
    ];

    public array $validHandlers = [
        'apcu'      => ApcuHandler::class,
        'dummy'     => DummyHandler::class,
        'file'      => FileHandler::class,
        'memcached' => MemcachedHandler::class,
        'predis'    => PredisHandler::class,
        'redis'     => RedisHandler::class,
        'wincache'  => WincacheHandler::class,
    ];

    public $cacheQueryString = false;

    public array $cacheStatusCodes = [200];

    public function __construct()
    {
        parent::__construct();

        $this->handler = env('cache.handler', 'redis');
        $this->backupHandler = env('cache.backupHandler', 'dummy');
        $this->prefix = env('cache.prefix', 'ci4_');
        $this->ttl = (int) env('cache.ttl', 60);

        $this->redis['host'] = env('cache.redis.host') ?: $this->detectRedisHost();
        $this->redis['port'] = (int) env('cache.redis.port', 6379);
        $this->redis['password'] = env('cache.redis.password', null);
        $this->redis['database'] = (int) env('cache.redis.database', 0);
        $this->redis['timeout'] = (float) env('cache.redis.timeout', 2.0);

        $this->cacheQueryString = env('cache.queryString', false);

        $this->cacheStatusCodes = array_map(
            'intval',
            explode(',', env('cache.statusCodes', '200'))
        );
    }

    private function detectRedisHost(): string
    {
        if (getenv('REDIS_HOST')) {
            return getenv('REDIS_HOST');
        }

        if (getenv('CACHE_REDIS_HOST')) {
            return getenv('CACHE_REDIS_HOST');
        }

        return 'redis';
    }
}