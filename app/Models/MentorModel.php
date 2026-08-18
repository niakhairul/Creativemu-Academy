<?php

namespace App\Models;

use CodeIgniter\Model; // <-- Cukup ini saja yang di-use

class MentorModel extends Model
{
    protected $table              = 'mentor';
    protected $primaryKey         = 'id_mentor';
    protected $allowedFields      = [
        'id_user', 
        'nama_mentor', 
        'email', 
        'telepon', 
        'keahlian', 
        'pengalaman', 
        'status', 
        'cv', 
        'bio'
    ];
    protected $useTimestamps      = true;
}