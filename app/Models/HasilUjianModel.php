<?php

namespace App\Models;

use CodeIgniter\Model;

class HasilUjianModel extends Model
{
    protected $table = 'hasil_ujian';
    protected $primaryKey = 'id_hasil_ujian';

    protected $allowedFields = [
        'id_user',
        'id_kelas',
        'benar',
        'jumlah_soal',
        'nilai',
        'status_penilaian',
        'status_kelulusan',
        'catatan_mentor',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
}