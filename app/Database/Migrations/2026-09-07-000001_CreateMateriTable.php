<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
class CreateMateriTable extends Migration
{
    public function up()
    {
        $fields = ['id_materi_kelas' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true], 'id_kelas' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true], 'judul_materi' => ['type' => 'VARCHAR', 'constraint' => 255], 'tipe_materi' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'Dokumen'], 'file_materi' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true], 'url_materi' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true], 'created_at' => ['type' => 'DATETIME', 'null' => true], 'updated_at' => ['type' => 'DATETIME', 'null' => true]];
        if (! $this->db->tableExists('materi')) { $this->forge->addField($fields); $this->forge->addKey('id_materi_kelas', true); $this->forge->addKey('id_kelas'); $this->forge->createTable('materi', true); return; }
        unset($fields['id_materi_kelas']); foreach ($fields as $name => $field) if (! $this->db->fieldExists($name, 'materi')) $this->forge->addColumn('materi', [$name => $field]);
    }
    public function down() { }
}
