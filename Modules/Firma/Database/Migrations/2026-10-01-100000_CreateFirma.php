<?php

namespace Modules\Firma\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFirma extends Migration
{
    public function up()
    {
        $id = ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true];
        $int = ['type' => 'INT', 'unsigned' => true];
        $date = ['type' => 'DATETIME'];
        $text = static fn(int $length) => ['type' => 'VARCHAR', 'constraint' => $length];
        $this->forge->addField([
            'id' => $id, 'nombre' => $text(200), 'origen' => $text(80),
            'referencia' => $text(100), 'created_by' => $int,
            'version_actual' => $int, 'created_at' => $date,
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['created_by', 'origen', 'referencia']);
        $this->forge->createTable('firma_documentos');
        $this->forge->addField([
            'id' => $id, 'documento_id' => $int, 'numero' => $int,
            'archivo' => $text(80), 'sha256' => $text(64), 'estado' => $text(30),
            'motivo' => $text(200), 'cargo' => $text(150), 'created_by' => $int, 'created_at' => $date,
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['documento_id', 'numero']);
        $this->forge->addForeignKey('documento_id', 'firma_documentos', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('firma_versiones');
        $this->forge->addField([
            'id' => $id, 'documento_id' => $int, 'version_base' => $int,
            'token_hash' => $text(64), 'estado' => $text(20),
            'motivo' => $text(200), 'cargo' => $text(150), 'estilo' => $int,
            'created_by' => $int, 'created_at' => $date, 'expires_at' => $date,
            'completed_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('token_hash');
        $this->forge->addKey(['documento_id', 'estado']);
        $this->forge->addForeignKey('documento_id', 'firma_documentos', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('firma_operaciones');
    }

    public function down()
    {
        $this->forge->dropTable('firma_operaciones');
        $this->forge->dropTable('firma_versiones');
        $this->forge->dropTable('firma_documentos');
    }
}
