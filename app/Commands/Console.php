<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Psy\Shell;

class Console extends BaseCommand
{
    protected $group       = 'Development';
    protected $name        = 'console';
    protected $description = 'Interactive developer console (PsySH)';

    public function run(array $params)
    {
        if (ENVIRONMENT === 'production') {
            CLI::error('Console is disabled in production.');
            return;
        }

        CLI::write('Starting CI4 Console (PsySH)...', 'green');

        $shell = new Shell();
        $shell->run();
    }
}