<?php

namespace App\Models;

use CodeIgniter\Model;

class JadwalModel extends Model
{
    protected $table            = 'jadwal'; 
    protected $primaryKey       = 'id_jadwal'; 
    protected $allowedFields    = [
        'id_kelas', 
        'pertemuan_ke', 
        'tanggal_kbm', 
        'waktu_mulai', 
        'waktu_selesai', 
        'materi', 
        'ruangan_atau_link', 
        'link_materi', 
        'file_pdf',
        'absensi_dibuka',
        'absensi_mulai',
        'absensi_selesai'
    ];
}