<?php

namespace App\Models;

use CodeIgniter\Model;

class AngketModel extends Model
{
    protected $table = 'angket_pertanyaan';
    protected $primaryKey = 'id_angket_pertanyaan';

    protected $allowedFields = [
        'id_users',
        'id_kelas',
        'materi',
        'mentor',
        'penyampaian',
        'manfaat',
        'saran'
    ];

    protected $useTimestamps = false;
}