<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $this->db->table('organizations')->insertBatch([
            [
                'name' => 'Acme Corp',
                'slug' => 'acme-corp',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Beta Studio',
                'slug' => 'beta-studio',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Startup Labs',
                'slug' => 'startup-labs',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}