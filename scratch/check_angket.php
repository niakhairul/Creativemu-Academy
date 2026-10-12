<?php
define('FCPATH', __DIR__ . '/../public/');
require __DIR__ . '/../app/Config/Paths.php';
$paths = new \Config\Paths();
require $paths->systemDirectory . '/Boot.php';
\CodeIgniter\Boot::bootTest($paths);

$db = \Config\Database::connect();
echo "jawaban_angket: " . ($db->tableExists('jawaban_angket') ? $db->table('jawaban_angket')->countAllResults() : 'tidak ada') . PHP_EOL;
echo "angket_pertanyaan: " . ($db->tableExists('angket_pertanyaan') ? $db->table('angket_pertanyaan')->countAllResults() : 'tidak ada') . PHP_EOL;
echo "angket_penilaian: " . ($db->tableExists('angket_penilaian') ? $db->table('angket_penilaian')->countAllResults() : 'tidak ada') . PHP_EOL;

if ($db->tableExists('jawaban_angket')) {
    $rows = $db->table('jawaban_angket')->limit(3)->get()->getResultArray();
    echo "Sample jawaban_angket: " . json_encode($rows) . PHP_EOL;
}
if ($db->tableExists('angket_penilaian')) {
    $rows2 = $db->table('angket_penilaian')->limit(3)->get()->getResultArray();
    echo "Sample angket_penilaian: " . json_encode($rows2) . PHP_EOL;
}
$angketModel = new \App\Models\LaporanAngketModel();
$list = $angketModel->getLaporanAngketList(['tahun' => date('Y'), 'bulan' => 'all', 'kategori' => 'all', 'id_kelas' => 'all', 'id_mentor' => 'all', 'tempat_pelatihan' => 'all']);
echo "Laporan Angket List Count: " . count($list) . PHP_EOL;
echo "List data: " . json_encode($list, JSON_PRETTY_PRINT) . PHP_EOL;

