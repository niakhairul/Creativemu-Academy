<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsensiModel extends Model
{
    protected $table = 'absensi';
    protected $primaryKey = 'id_absensi';

    protected $allowedFields = [
        'id_jadwal_kelas',
        'id_user',
        'status',
        'latitude',
        'longitude',
        'jarak',
        'waktu_absen',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
}