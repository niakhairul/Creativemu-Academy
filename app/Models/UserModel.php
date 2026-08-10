<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['nama', 'jenis_kelamin', 'email', 'no_hp', 'asal_sekolah', 'password', 'role', 'status'];
    protected $useTimestamps    = true;
}