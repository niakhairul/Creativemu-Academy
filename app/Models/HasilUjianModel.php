<?php

namespace App\Models;

use CodeIgniter\Model;

class HasilUjianModel extends Model
{
    protected $table = 'nilai_ujian';
    protected $primaryKey = 'id_nilai_ujian';
    protected $returnType = 'array';
    protected $allowedFields = [
        'id_user',
        'id_kelas',
        'id_ujian',
        'benar',
        'jumlah_soal',
        'nilai',
        'nilai_awal',
        'nilai_remidi',
        'status_kelulusan',
        'status_remidi',
        'is_remidi',
        'catatan',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;
}
