<?php

namespace App\Models;

use CodeIgniter\Model;

class MentorModel extends Model
{
    protected $table            = 'mentor';
    protected $primaryKey       = 'id_mentor';
    protected $useAutoIncrement = true;
    
    // TAMBAHKAN BARIS INI:
    protected $protectFields    = false; 

    protected $allowedFields    = [
        'id_user',
        'id_users',
        'nama_mentor', 
        'email', 
        'telepon', 
        'keahlian', 
        'pengalaman', 
        'status', 
        'cv', 
        'bio'
    ];
    protected $useTimestamps    = true;
}