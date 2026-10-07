<?php
namespace App\Controllers;
class DbTest extends BaseController {
    public function index() {
        $db = \Config\Database::connect();
        echo "KELAS:\n";
        print_r($db->query('SELECT id_kelas, nama_kelas, lokasi_pelatihan FROM kelas LIMIT 5')->getResultArray());
        echo "\nPENDAFTARAN:\n";
        print_r($db->query('SELECT id_pendaftaran, tempat_pelatihan FROM pendaftaran LIMIT 5')->getResultArray());
        
        $fields = $db->getFieldNames('pendaftaran');
        echo "\nFIELDS IN PENDAFTARAN:\n";
        print_r($fields);
    }
}

