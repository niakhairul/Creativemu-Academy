<?php

namespace App\Models;

use CodeIgniter\Model;

class PendaftaranModel extends Model
{
    protected $table            = 'pendaftaran';
    protected $primaryKey       = 'id_pendaftaran'; // Pastikan nama kolom ini SAMA PERSIS dengan di database
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    
    protected $allowedFields    = [
        'id_kelas',
        'pilihan_pelatihan',
        'pilihan_kelas',
        'tanggal_mulai_kelas',
        'nama',
        'email',
        'no_hp',
        'alamat',
        'ttl',
        'jenis_kelamin',
        'pendidikan_terakhir',
        'status',
        'lokasi_pelatihan',
        'kategori_kelas',
        'pas_foto',
        'jenis_kelas',
        'metode_pembelajaran',
        'metode_pembayaran',
        'bukti_pembayaran',
        'status_pembayaran',
        'alasan_penolakan',
        'persetujuan_syarat'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}