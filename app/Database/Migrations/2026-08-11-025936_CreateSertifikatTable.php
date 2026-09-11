<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSertifikatTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_users' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_kelas' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'no_sertifikat' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => true,
            ],
            'tanggal_penerbitan' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_users', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_kelas', 'kelas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('sertifikat');
    }

    public function down()
    {
        $this->forge->dropTable('sertifikat');
    }
}