<?php

namespace App\Models;

use CodeIgniter\Model;

class AngketJawabanModel extends Model
{
    protected $table = 'angket_penilaian';
    protected $primaryKey = 'id_angket'; // Sesuai dengan primary key di database Anda

    protected $allowedFields = [
        'id_kelas',
        'id_peserta', // Sesuai dengan nama kolom di database
        'rating',
        'ulasan',
        'created_at'
    ];
}