<?php
namespace App\Controllers;
use App\Models\PendaftaranModel;
use App\Models\SertifikatModel;

class DebugSertifikat extends BaseController
{
    public function index()
    {
        $userId = 18;
        $id_kelas = 2;

        $pendaftaran = (new PendaftaranModel())
            ->where("id_users", $userId)
            ->where("id_kelas", $id_kelas)
            ->first();

        $sertifikat = null;
        if ($pendaftaran) {
            $sertifikat = (new SertifikatModel())
                ->where("id_kelas", $pendaftaran["id_kelas"])
                ->where("id_user", $userId)
                ->first();
        }

        return $this->response->setJSON([
            "userId" => $userId,
            "id_kelas" => $id_kelas,
            "pendaftaran" => $pendaftaran,
            "sertifikat" => $sertifikat
        ]);
    }
}

