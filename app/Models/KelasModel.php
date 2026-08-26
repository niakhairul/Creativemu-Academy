<?php
namespace App\Models;

use CodeIgniter\Model;

class KelasModel extends Model
{
    protected $table            = 'kelas';
    protected $primaryKey       = 'id_kelas';
    
    // Mengaktifkan fitur otomatis created_at dan updated_at
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    // Kolom yang diizinkan untuk diisi (Mass Assignment) sesuai struktur database Anda
    protected $allowedFields    = [
        'id_mentor',
        'kategori',
        'nama_kelas',
        'jumlah_pertemuan',
        'deskripsi',
        'kapasitas',
        'tanggal_mulai_kelas', // Menyimpan array jadwal pertemuan berformat JSON
        'ringkasan',
        'harga_reguler',       
        'harga_privat',      
        'status',        // 'Aktif' atau 'Selesai'
        'tipe_kelas',   // Tambahan: 'Online' atau 'Offline'
        'lokasi_media',  // Tambahan: Link Zoom / Alamat Ruangan
        'thumbnail'      // Tambahan: Nama file foto/gambar kelas
    ];
}