<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        $db = $this->db;

        // Obtener organización default
        $org = $db->table('organizations')
            ->where('slug', 'default-org')
            ->get()
            ->getRowArray();

        if (!$org) {
            throw new \RuntimeException('[ProjectSeeder] Default organization not found. Run SaasBaseSeeder first.');
        }

        $orgId = $org['id'];

        // Proyectos demo (starter kit UX)
        $demoProjects = [
            [
                'name' => 'First Project',
                'organization_id' => $orgId,
            ],
            [
                'name' => 'Internal Dashboard',
                'organization_id' => $orgId,
            ],
        ];

        foreach ($demoProjects as $project) {

            $exists = $db->table('projects')
                ->where('name', $project['name'])
                ->where('organization_id', $orgId)
                ->get()
                ->getRowArray();

            if (!$exists) {
                $db->table('projects')->insert([
                    'name'             => $project['name'],
                    'organization_id'  => $orgId,
                    'created_at'       => date('Y-m-d H:i:s'),
                    'updated_at'       => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}