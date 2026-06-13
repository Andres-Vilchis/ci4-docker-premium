<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProjects extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'organization_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],

            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addKey('organization_id');

        $this->forge->addForeignKey(
            'organization_id',
            'organizations',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->createTable('projects');
    }

    public function down()
    {
        $this->forge->dropTable('projects', true);
    }
}
