<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Throwable;

class Health extends ResourceController
{
    public function index()
    {
        $status = [
            'app' => 'ok',
            'database' => 'unknown',
            'redis' => 'unknown',
        ];

        // DB SAFE CHECK
        try {
            $db = \Config\Database::connect();
            $db->query('SELECT 1');
            $status['database'] = 'ok';
        } catch (Throwable $e) {
            $status['database'] = 'fail';
        }

        // REDIS SAFE CHECK
        try {
            $redis = new \Redis();
            $redis->connect(
                env('cache.redis.host', 'redis'),
                (int) env('cache.redis.port', 6379),
                1.5
            );
            $status['redis'] = $redis->ping() ? 'ok' : 'fail';
        } catch (Throwable $e) {
            $status['redis'] = 'fail';
        }

        return $this->response->setJSON($status);
    }
}