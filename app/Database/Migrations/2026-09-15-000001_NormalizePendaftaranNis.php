<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class NormalizePendaftaranNis extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('nis', 'pendaftaran')) {
            $this->forge->addColumn('pendaftaran', [
                'nis' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 8,
                    'null'       => true,
                    'after'      => 'no_hp',
                ],
            ]);
        } else {
            $this->forge->modifyColumn('pendaftaran', [
                'nis' => [
                    'name'       => 'nis',
                    'type'       => 'VARCHAR',
                    'constraint' => 8,
                    'null'       => true,
                ],
            ]);
        }

        $indexes = $this->db->query("SHOW INDEX FROM pendaftaran WHERE Key_name = 'idx_pendaftaran_nis_unique'")->getResultArray();
        if (empty($indexes)) {
            $this->db->query('CREATE UNIQUE INDEX idx_pendaftaran_nis_unique ON pendaftaran (nis)');
        }
    }

    public function down()
    {
        $indexes = $this->db->query("SHOW INDEX FROM pendaftaran WHERE Key_name = 'idx_pendaftaran_nis_unique'")->getResultArray();
        if (!empty($indexes)) {
            $this->db->query('DROP INDEX idx_pendaftaran_nis_unique ON pendaftaran');
        }
    }
}
