<?php

namespace App\Models;

use CodeIgniter\Model;

class PendaftaranModel extends Model
{
    protected $table = 'pendaftaran';
    protected $primaryKey = 'id_pendaftaran';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_users',
        'id_kelas',
        'jenis_kelas',
        'tanggal_daftar',
        'bukti_pembayaran',
        'metode_pembayaran',
        'metode_pembelajaran',
        'jenis_kelamin',
        'pendidikan_terakhir',
        'status_pembayaran',
        'status_pendaftaran'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}