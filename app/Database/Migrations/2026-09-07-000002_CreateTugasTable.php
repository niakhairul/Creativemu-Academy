<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTugasTable extends Migration
{
    public function up()
    {
        $fields = [
            'id_tugas' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_kelas' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'judul_tugas' => ['type' => 'VARCHAR', 'constraint' => 255],
            'deskripsi' => ['type' => 'TEXT', 'null' => true],
            'deadline' => ['type' => 'DATETIME', 'null' => true],
            'file_tugas' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ];

        if (! $this->db->tableExists('tugas')) {
            $this->forge->addField($fields);
            $this->forge->addKey('id_tugas', true);
            $this->forge->addKey('id_kelas');
            $this->forge->createTable('tugas', true);
            return;
        }

        unset($fields['id_tugas']);
        foreach ($fields as $name => $field) {
            if (! $this->db->fieldExists($name, 'tugas')) {
                $this->forge->addColumn('tugas', [$name => $field]);
            }
        }
    }

    public function down()
    {
    }
}
