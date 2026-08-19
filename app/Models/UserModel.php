<?php namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id_users';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    // Tambahkan 'no_hp' dan 'foto_profil' agar sinkron dengan database
    protected $allowedFields    = ['nama', 'email', 'password', 'role', 'no_hp', 'foto_profil'];
    
    protected $useTimestamps    = true; // Aktifkan jika Anda menggunakan created_at dan updated_at otomatis
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
}