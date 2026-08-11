<?php

namespace App\Models;

use CodeIgniter\Model;

class JadwalKelasModel extends Model
{
    protected $table = 'jadwal_kelas';
    protected $primaryKey = 'id_jadwal_kelas';

    protected $allowedFields = [
        'id_kelas',
        'pertemuan_ke',
        'materi',
        'tanggal_kbm',
        'jam_selesai',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
}