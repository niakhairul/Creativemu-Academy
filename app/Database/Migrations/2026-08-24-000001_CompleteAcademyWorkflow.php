<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CompleteAcademyWorkflow extends Migration
{
    public function up()
    {
        $this->ensureUsers();
        $this->ensureMentor();
        $this->ensureKelas();
        $this->ensurePendaftaran();
        $this->ensureJadwalKelas();
        $this->ensureAbsensi();
        $this->ensurePengumpulanTugas();
        $this->ensureHasilUjian();
    }

    public function down()
    {
        // Migration pelengkap ini sengaja tidak drop tabel agar data existing aman.
    }

    private function addMissingColumns(string $table, array $fields): void
    {
        foreach ($fields as $name => $field) {
            if (! $this->db->fieldExists($name, $table)) {
                $this->forge->addColumn($table, [$name => $field]);
            }
        }
    }

    private function ensureUsers(): void
    {
        $fields = [
            'id_users' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama' => ['type' => 'VARCHAR', 'constraint' => 150],
            'email' => ['type' => 'VARCHAR', 'constraint' => 150],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255],
            'role' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'peserta'],
            'no_hp' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'jenis_kelamin' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'asal_sekolah' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'foto' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'foto_profil' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ];

        if (! $this->db->tableExists('users')) {
            $this->forge->addField($fields);
            $this->forge->addKey('id_users', true);
            $this->forge->createTable('users', true);
            return;
        }

        unset($fields['id_users']);
        $this->addMissingColumns('users', $fields);
    }

    private function ensureMentor(): void
    {
        $fields = [
            'id_mentor' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_users' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'nama_mentor' => ['type' => 'VARCHAR', 'constraint' => 150],
            'email' => ['type' => 'VARCHAR', 'constraint' => 150],
            'telepon' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'keahlian' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'pengalaman' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'bio' => ['type' => 'TEXT', 'null' => true],
            'status' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'Aktif'],
            'cv' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ];

        if (! $this->db->tableExists('mentor')) {
            $this->forge->addField($fields);
            $this->forge->addKey('id_mentor', true);
            $this->forge->createTable('mentor', true);
            return;
        }

        unset($fields['id_mentor']);
        $this->addMissingColumns('mentor', $fields);
    }

    private function ensureKelas(): void
    {
        $fields = [
            'id_kelas' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_mentor' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'kategori' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'nama_kelas' => ['type' => 'VARCHAR', 'constraint' => 150],
            'jumlah_pertemuan' => ['type' => 'INT', 'constraint' => 3, 'default' => 6],
            'deskripsi' => ['type' => 'TEXT', 'null' => true],
            'kapasitas' => ['type' => 'INT', 'constraint' => 5, 'default' => 0],
            'tanggal_mulai_kelas' => ['type' => 'DATETIME', 'null' => true],
            'ringkasan' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'harga' => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0],
            'status' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'Aktif'],
            'tipe_kelas' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'lokasi_media' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'thumbnail' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ];

        if (! $this->db->tableExists('kelas')) {
            $this->forge->addField($fields);
            $this->forge->addKey('id_kelas', true);
            $this->forge->createTable('kelas', true);
            return;
        }

        unset($fields['id_kelas']);
        $this->addMissingColumns('kelas', $fields);
    }

    private function ensurePendaftaran(): void
    {
        $fields = [
            'id_pendaftaran' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_users' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'id_kelas' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'metode_pembelajaran' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'metode_pembayaran' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'bukti_pembayaran' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'status_pendaftaran' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'Menunggu'],
            'status_pembayaran' => ['type' => 'VARCHAR', 'constraint' => 40, 'default' => 'Menunggu Bukti'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ];

        if (! $this->db->tableExists('pendaftaran')) {
            $this->forge->addField($fields);
            $this->forge->addKey('id_pendaftaran', true);
            $this->forge->createTable('pendaftaran', true);
            return;
        }

        unset($fields['id_pendaftaran']);
        $this->addMissingColumns('pendaftaran', $fields);
    }

    private function ensureJadwalKelas(): void
    {
        $fields = [
            'id_jadwal_kelas' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_kelas' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'pertemuan_ke' => ['type' => 'INT', 'constraint' => 3],
            'materi' => ['type' => 'TEXT', 'null' => true],
            'tanggal_kbm' => ['type' => 'DATETIME', 'null' => true],
            'jam_selesai' => ['type' => 'TIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ];

        if (! $this->db->tableExists('jadwal_kelas')) {
            $this->forge->addField($fields);
            $this->forge->addKey('id_jadwal_kelas', true);
            $this->forge->createTable('jadwal_kelas', true);
            return;
        }

        unset($fields['id_jadwal_kelas']);
        $this->addMissingColumns('jadwal_kelas', $fields);
    }

    private function ensureAbsensi(): void
    {
        $fields = [
            'id_absensi' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_jadwal_kelas' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'id_user' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'status' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'hadir'],
            'waktu_absen' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ];

        if (! $this->db->tableExists('absensi')) {
            $this->forge->addField($fields);
            $this->forge->addKey('id_absensi', true);
            $this->forge->createTable('absensi', true);
            return;
        }

        unset($fields['id_absensi']);
        $this->addMissingColumns('absensi', $fields);
    }

    private function ensurePengumpulanTugas(): void
    {
        $fields = [
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_tugas' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'id_users' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'file_tugas' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'nilai' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'null' => true],
            'status' => ['type' => 'VARCHAR', 'constraint' => 40, 'default' => 'Belum Dinilai'],
        ];

        if (! $this->db->tableExists('pengumpulan_tugas')) {
            $this->forge->addField($fields);
            $this->forge->addKey('id', true);
            $this->forge->createTable('pengumpulan_tugas', true);
            return;
        }

        unset($fields['id']);
        $this->addMissingColumns('pengumpulan_tugas', $fields);
    }

    private function ensureHasilUjian(): void
    {
        $fields = [
            'id_hasil_ujian' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_user' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'id_users' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'id_kelas' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'benar' => ['type' => 'INT', 'constraint' => 5, 'default' => 0],
            'jumlah_soal' => ['type' => 'INT', 'constraint' => 5, 'default' => 0],
            'nilai' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 0],
            'status_penilaian' => ['type' => 'VARCHAR', 'constraint' => 40, 'default' => 'menunggu'],
            'status_kelulusan' => ['type' => 'VARCHAR', 'constraint' => 40, 'default' => 'menunggu'],
            'catatan_mentor' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ];

        if (! $this->db->tableExists('hasil_ujian')) {
            $this->forge->addField($fields);
            $this->forge->addKey('id_hasil_ujian', true);
            $this->forge->createTable('hasil_ujian', true);
            return;
        }

        unset($fields['id_hasil_ujian']);
        $this->addMissingColumns('hasil_ujian', $fields);
    }
}
