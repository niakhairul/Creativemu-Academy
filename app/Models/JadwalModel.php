<?php

namespace App\Models;

use CodeIgniter\Model;

class JadwalModel extends Model
{
    protected $tableSelected      = 'jadwal'; // Sesuaikan dengan nama tabel di database kamu
    protected $table            = 'jadwal'; 
    protected $primaryKey       = 'id_jadwal'; // Sesuaikan primary key tabel jadwal kamu
    protected $allowedFields    = [
        'id_kelas', 
        'pertemuan_ke', 
        'tanggal_kbm', 
        'waktu_mulai', 
        'waktu_selesai', 
        'materi', 
        'ruangan_atau_link', 
        'link_materi', 
        'file_pdf'
    ];
}