<?php namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users'; // Sesuaikan dengan nama tabel Anda
    protected $primaryKey       = 'id_users'; // <-- Ubah dari 'user_id' menjadi 'id_users' (atau nama kolom ID yang asli di database)
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['nama', 'email', 'password', 'role']; // Sesuaikan kolom tabel Anda
}