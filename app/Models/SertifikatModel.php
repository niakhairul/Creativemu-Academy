<?php

namespace App\Models;

use CodeIgniter\Model;

class SertifikatModel extends Model
{
    protected $table            = 'sertifikat';
    protected $primaryKey       = 'id_sertifikat';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields = [
        'id_peserta',
        'nomor_sertifikat',
        'id_user',
        'id_kelas',
        'tanggal_terbit',
        'file_sertifikat',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}