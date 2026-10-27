<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsRegisteredToUsers extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('is_registered', 'users')) {
            $this->forge->addColumn('users', [
                'is_registered' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 0,
                    'null'       => false,
                    'after'      => 'role',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('is_registered', 'users')) {
            $this->forge->dropColumn('users', 'is_registered');
        }
    }
}

