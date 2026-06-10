<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Redis;

class QueueWorker extends BaseCommand
{
    protected $group = 'Queue';
    protected $name = 'queue:work';
    protected $description = 'Run Redis queue worker';

    public function run(array $params)
    {
        CLI::write('Queue worker started...', 'green');

        $redis = new Redis();
        $redis->connect('redis', 6379);

        while (true) {
            try {
                $job = $redis->lPop('queue:pending');

                if (!$job) {
                    sleep(2);
                    continue;
                }

                CLI::write("Processing job: $job");

                // Simulated processing
                $success = true;

                if ($success) {
                    $redis->rPush('queue:processed', $job);
                } else {
                    $redis->rPush('queue:failed', $job);
                }

            } catch (\Throwable $e) {
                CLI::error($e->getMessage());
            }
        }
    }
}