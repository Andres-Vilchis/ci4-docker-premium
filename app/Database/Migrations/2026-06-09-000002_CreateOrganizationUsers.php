<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrganizationUsers extends Migration
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

            'user_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'comment'  => 'Reserved for Shield/Identity integration in Professional edition',
            ],

            'role' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'member',
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
        $this->forge->addUniqueKey( ['organization_id', 'user_id'], 'org_user_unique' );
        $this->forge->addKey('organization_id');
        $this->forge->addKey('user_id');

        /**
         * Starter Edition:
         * organizations exists in Starter.
         */
        $this->forge->addForeignKey(
            'organization_id',
            'organizations',
            'id',
            'CASCADE',
            'CASCADE'
        );

        /**
         * Professional Edition:
         * users table comes from Shield.
         *
         * Disabled in Starter to avoid migration failures
         * on fresh installations.
         */
        // $this->forge->addForeignKey(
        //     'user_id',
        //     'users',
        //     'id',
        //     'CASCADE',
        //     'CASCADE'
        // );

        $this->forge->createTable('organization_users');
    }

    public function down()
    {
        $this->forge->dropTable('organization_users', true);
    }
}