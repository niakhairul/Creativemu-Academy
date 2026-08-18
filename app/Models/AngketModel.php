<?php

namespace App\Models;

use CodeIgniter\Model;

class AngketModel extends Model
{
    protected $table = 'angket';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id',
        'kelas_id',
        'materi',
        'mentor',
        'penyampaian',
        'manfaat',
        'saran'
    ];

    protected $useTimestamps = false;
}