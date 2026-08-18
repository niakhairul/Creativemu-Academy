<?php

namespace App\Models;

use CodeIgniter\Model;

class SertifikatModel extends Model
{
    protected $table            = 'sertifikat';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields    = [
        'no_sertifikat', 
        'id_users', 
        'id_kelas', 
        'tanggal_penerbitan', 
        'file_sertifikat',
        'created_at',
        'updated_at'
    ];

    // Timestamp otomatis
    protected $useTimestamps   = true;
    protected $dateFormat      = 'datetime';
    protected $createdField    = 'created_at';
    protected $updatedField    = 'updated_at';
}