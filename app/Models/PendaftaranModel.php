<?php

namespace App\Models;

use CodeIgniter\Model;

class PendaftaranModel extends Model
{
    protected $table = 'pendaftaran';

    protected $primaryKey = 'id';

    protected $allowedFields = [

        'user_id',
        'kelas_id',

        'metode_pembelajaran',
        'metode_pembayaran',

        'bukti_pembayaran',

        'status_pendaftaran',
        'status_pembayaran'

    ];
}