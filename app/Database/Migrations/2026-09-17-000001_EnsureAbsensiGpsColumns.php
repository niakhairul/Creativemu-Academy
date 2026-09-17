<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EnsureAbsensiGpsColumns extends Migration
{
    public function up()
    {
        // 1. Pastikan kolom-kolom GPS pada tabel absensi
        if ($this->db->tableExists('absensi')) {
            if (!$this->db->fieldExists('id_jadwal', 'absensi')) {
                $this->forge->addColumn('absensi', [
                    'id_jadwal' => [
                        'type'       => 'INT',
                        'constraint' => 11,
                        'unsigned'   => true,
                        'null'       => true,
                        'after'      => 'id_absensi',
                    ],
                ]);
            }

            if (!$this->db->fieldExists('id_jadwal_kelas', 'absensi')) {
                $this->forge->addColumn('absensi', [
                    'id_jadwal_kelas' => [
                        'type'       => 'INT',
                        'constraint' => 11,
                        'unsigned'   => true,
                        'null'       => true,
                        'after'      => 'id_jadwal',
                    ],
                ]);
            }

            if (!$this->db->fieldExists('latitude', 'absensi')) {
                $this->forge->addColumn('absensi', [
                    'latitude' => [
                        'type'       => 'DECIMAL',
                        'constraint' => '10,8',
                        'null'       => true,
                        'after'      => 'status',
                    ],
                ]);
            }

            if (!$this->db->fieldExists('longitude', 'absensi')) {
                $this->forge->addColumn('absensi', [
                    'longitude' => [
                        'type'       => 'DECIMAL',
                        'constraint' => '11,8',
                        'null'       => true,
                        'after'      => 'latitude',
                    ],
                ]);
            }

            if (!$this->db->fieldExists('jarak', 'absensi')) {
                $this->forge->addColumn('absensi', [
                    'jarak' => [
                        'type'       => 'INT',
                        'constraint' => 11,
                        'null'       => true,
                        'after'      => 'longitude',
                    ],
                ]);
            }
        }

        // 2. Pastikan kolom-kolom jadwal untuk absensi & GPS
        if ($this->db->tableExists('jadwal')) {
            if (!$this->db->fieldExists('token_absen', 'jadwal')) {
                $this->forge->addColumn('jadwal', [
                    'token_absen' => [
                        'type'       => 'VARCHAR',
                        'constraint' => 10,
                        'null'       => true,
                    ],
                ]);
            }

            if (!$this->db->fieldExists('absensi_dibuka', 'jadwal')) {
                $this->forge->addColumn('jadwal', [
                    'absensi_dibuka' => [
                        'type'       => 'TINYINT',
                        'constraint' => 1,
                        'default'    => 0,
                    ],
                ]);
            }

            if (!$this->db->fieldExists('absensi_mulai', 'jadwal')) {
                $this->forge->addColumn('jadwal', [
                    'absensi_mulai' => [
                        'type' => 'DATETIME',
                        'null' => true,
                    ],
                ]);
            }

            if (!$this->db->fieldExists('absensi_selesai', 'jadwal')) {
                $this->forge->addColumn('jadwal', [
                    'absensi_selesai' => [
                        'type' => 'DATETIME',
                        'null' => true,
                    ],
                ]);
            }

            if (!$this->db->fieldExists('latitude', 'jadwal')) {
                $this->forge->addColumn('jadwal', [
                    'latitude' => [
                        'type'       => 'DECIMAL',
                        'constraint' => '10,8',
                        'null'       => true,
                    ],
                ]);
            }

            if (!$this->db->fieldExists('longitude', 'jadwal')) {
                $this->forge->addColumn('jadwal', [
                    'longitude' => [
                        'type'       => 'DECIMAL',
                        'constraint' => '11,8',
                        'null'       => true,
                    ],
                ]);
            }

            if (!$this->db->fieldExists('radius_meter', 'jadwal')) {
                $this->forge->addColumn('jadwal', [
                    'radius_meter' => [
                        'type'       => 'INT',
                        'constraint' => 11,
                        'default'    => 100,
                        'null'       => true,
                    ],
                ]);
            }
        }

        // 3. Pastikan tabel lokasi_pelatihan ada dan terisi
        if (!$this->db->tableExists('lokasi_pelatihan')) {
            $this->forge->addField([
                'id_lokasi' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'nama_lokasi' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 150,
                ],
                'alamat' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'latitude' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '10,8',
                    'null'       => true,
                ],
                'longitude' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '11,8',
                    'null'       => true,
                ],
                'radius_meter' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'default'    => 100,
                ],
                'is_online' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 0,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $this->forge->addKey('id_lokasi', true);
            $this->forge->createTable('lokasi_pelatihan', true);
        }

        // Seeding lokasi default jika kosong
        if ($this->db->tableExists('lokasi_pelatihan')) {
            $count = $this->db->table('lokasi_pelatihan')->countAllResults();
            if ($count === 0) {
                $now = date('Y-m-d H:i:s');
                $this->db->table('lokasi_pelatihan')->insertBatch([
                    [
                        'nama_lokasi'  => 'Kampus Utama Creativemu (Sedayu)',
                        'alamat'       => 'Jl. Gn. Bulu No 89, RT.34, Bandut Lor, Argorejo, Sedayu, Bantul, Yogyakarta',
                        'latitude'     => -7.81893300,
                        'longitude'    => 110.28581300,
                        'radius_meter' => 100,
                        'is_online'    => 0,
                        'created_at'   => $now,
                        'updated_at'   => $now,
                    ],
                    [
                        'nama_lokasi'  => 'Cabang Glagahsari (Umbulharjo)',
                        'alamat'       => 'Jl. Glagahsari No. 42, Umbulharjo, Yogyakarta',
                        'latitude'     => -7.81643400,
                        'longitude'    => 110.38763500,
                        'radius_meter' => 100,
                        'is_online'    => 0,
                        'created_at'   => $now,
                        'updated_at'   => $now,
                    ],
                    [
                        'nama_lokasi'  => 'Kelas Online / Daring',
                        'alamat'       => 'Online / Jarak Jauh via Zoom & LMS',
                        'latitude'     => 0.00000000,
                        'longitude'    => 0.00000000,
                        'radius_meter' => 9999999,
                        'is_online'    => 1,
                        'created_at'   => $now,
                        'updated_at'   => $now,
                    ],
                ]);
            }
        }
    }

    public function down()
    {
        // Tetap aman tanpa drop kolom
    }
}
