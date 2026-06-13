<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Redis;
use Throwable;

class QueueWorker extends BaseCommand
{
    protected $group = 'Queue';

    protected $name = 'queue:work';

    protected $description = 'Run Redis queue worker';

    public function run(array $params): void
    {
        CLI::write(
            'Queue worker started...',
            'green'
        );

        $redis = new Redis();

        $redis->connect('redis', 6379);

        $running = true;

        while ($running) {
            try {
                $job = $redis->lPop(
                    'queue:pending'
                );

                if ($job === false) {
                    sleep(2);
                    continue;
                }

                CLI::write(
                    "Processing job: {$job}"
                );

                $processed = true;

                if ($processed) {
                    $redis->rPush(
                        'queue:processed',
                        $job
                    );
                } else {
                    $redis->rPush(
                        'queue:failed',
                        $job
                    );
                }
            } catch (Throwable $e) {
                CLI::error(
                    $e->getMessage()
                );

                sleep(1);
            }
        }
    }
}