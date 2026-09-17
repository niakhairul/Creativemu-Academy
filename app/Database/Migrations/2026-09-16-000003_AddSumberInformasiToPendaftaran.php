<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSumberInformasiToPendaftaran extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('sumber_informasi', 'pendaftaran')) {
            $this->forge->addColumn('pendaftaran', [
                'sumber_informasi' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => true,
                    'after' => 'metode_pembelajaran',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('sumber_informasi', 'pendaftaran')) {
            $this->forge->dropColumn('pendaftaran', 'sumber_informasi');
        }
    }
}
