<?php

namespace App\Models;

use CodeIgniter\Model;

class LokasiPelatihanModel extends Model
{
    protected $table = 'lokasi_pelatihan';
    protected $primaryKey = 'id_lokasi';
    protected $returnType = 'array';
    protected $allowedFields = [
        'nama_lokasi',
        'alamat',
        'latitude',
        'longitude',
        'radius_meter',
        'is_online',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;
}
