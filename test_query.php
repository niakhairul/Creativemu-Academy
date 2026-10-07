<?php
$conn = new mysqli('localhost', 'root', '', 'creativemu_academy');

$id_users = 18;
$id_kelas = 2;
$id_ujian = 2;

// Get MAX ID
$res = $conn->query("SELECT MAX(id_nilai_ujian) as max_id FROM nilai_ujian");
$row = $res->fetch_assoc();
$next_id = ($row['max_id'] ?? 0) + 1;

$cek = $conn->query("SELECT * FROM nilai_ujian WHERE id_users = $id_users AND id_kelas = $id_kelas AND id_ujian = $id_ujian");
if ($cek && $cek->num_rows > 0) {
    // Update
    $sql = "UPDATE nilai_ujian SET 
            nilai = 60.00,
            nilai_awal = 60.00,
            nilai_remidi = NULL,
            status_kelulusan = 'remidi',
            status_remidi = 'wajib',
            is_remidi = 0
            WHERE id_users = $id_users AND id_kelas = $id_kelas AND id_ujian = $id_ujian";
    $conn->query($sql);
    echo "Updated data for Aliando.\n";
} else {
    // Insert
    $sql = "INSERT INTO nilai_ujian 
            (id_nilai_ujian, id_ujian, id_users, id_kelas, nilai, nilai_awal, nilai_remidi, benar, jumlah_soal, status_kelulusan, status_remidi, is_remidi, created_at, updated_at) 
            VALUES 
            ($next_id, $id_ujian, $id_users, $id_kelas, 60.00, 60.00, NULL, 6, 10, 'remidi', 'wajib', 0, NOW(), NOW())";
    $conn->query($sql);
    if ($conn->error) {
        echo "Error Insert: " . $conn->error . "\n";
    } else {
        echo "Inserted data for Aliando with ID $next_id.\n";
    }
}

// Tampilkan hasil akhir
$res = $conn->query("SELECT id_users, id_kelas, id_ujian, nilai, nilai_awal, status_kelulusan, status_remidi FROM nilai_ujian WHERE id_users = $id_users AND id_kelas = $id_kelas");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        echo json_encode($row, JSON_PRETTY_PRINT) . "\n";
    }
}
?>

