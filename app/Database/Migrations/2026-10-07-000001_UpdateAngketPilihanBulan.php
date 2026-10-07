<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateAngketPilihanBulan extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('angket_pertanyaan')) {
            $bulan = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];
            $jsonBulan = json_encode($bulan, JSON_UNESCAPED_UNICODE);

            $this->db->table('angket_pertanyaan')
                ->where('id_angket_pertanyaan', 24)
                ->orGroupStart()
                    ->where('kategori', 'Customer Insight')
                    ->like('pertanyaan', 'Bulan')
                ->groupEnd()
                ->update(['opsi_jawaban' => $jsonBulan]);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('angket_pertanyaan')) {
            $bulanLama = ['Juli', 'Agustus'];
            $jsonBulanLama = json_encode($bulanLama, JSON_UNESCAPED_UNICODE);

            $this->db->table('angket_pertanyaan')
                ->where('id_angket_pertanyaan', 24)
                ->orGroupStart()
                    ->where('kategori', 'Customer Insight')
                    ->like('pertanyaan', 'Bulan')
                ->groupEnd()
                ->update(['opsi_jawaban' => $jsonBulanLama]);
        }
    }
}

