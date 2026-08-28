<?php
namespace App\Models;

use CodeIgniter\Model;

class KelasModel extends Model
{
    protected $table              = 'kelas';
    protected $primaryKey         = 'id_kelas';
    
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields    = [
        'id_mentor',
        'kategori',
        'nama_kelas',
        'jumlah_pertemuan',
        'deskripsi',
        'kapasitas',
        'tanggal_mulai_kelas', 
        'ringkasan',
        'harga_reguler',       
        'harga_privat',      
        'status',      
        'tipe_kelas',   
        'lokasi_media',
        'thumbnail'    
    ];

    // TAMBAHKAN FUNGSI INI UNTUK MENGAMBIL DATA BESERTA NAMA MENTOR
    public function getKelasWithMentor()
    {
        return $this->select('kelas.*, mentor.nama_mentor') // Sesuaikan nama tabel mentor jika berbeda (misal: 'tb_mentor')
                    ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')
                    ->findAll();
    }
}