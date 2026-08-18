<?php

namespace App\Models;

use CodeIgniter\Model;

class PengumpulanTugasModel extends Model
{
    protected $table = 'pengumpulan_tugas';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'id_tugas',
        'id_users',
        'file_tugas',
        'nilai',
        'status'
    ];

    protected $useTimestamps = false;
}