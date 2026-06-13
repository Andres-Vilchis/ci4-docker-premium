<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Bootstrap base SaaS
        $this->call('SaasBaseSeeder');

        // Domain layer seeds
        $this->call('ProjectSeeder');
    }
}