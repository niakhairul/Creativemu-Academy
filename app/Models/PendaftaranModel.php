<?php

namespace App\Models;

use CodeIgniter\Model;

class PendaftaranModel extends Model
{
    protected $table            = 'pendaftaran';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['id_users', 'kelas_id', 'metode_pembelajaran', 'metode_pembayaran', 'bukti_pembayaran', 'status_pendaftaran', 'status_pembayaran'];
    protected $useTimestamps    = true;

    // Relasi untuk mengambil detail data peserta dan kelas
    public function getPendaftaranDetail()
    {
        return $this->select('pendaftaran.*, users.nama as nama_peserta, kelas.nama_kelas')
                    ->join('users', 'users.id = pendaftaran.id_users')
                    ->join('kelas', 'kelas.id = pendaftaran.kelas_id')
                    ->findAll();
    }
}