<?php

// app/Database/Migrations/2025-08-24-000002_CreateSignatureJobs.php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSignatureJobs extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'             => ['type'=>'VARCHAR','constraint'=>36], // UUID
            'user_id'        => ['type'=>'INT','constraint'=>11,'unsigned'=>true],
            'profile_id'     => ['type'=>'INT','constraint'=>11,'unsigned'=>true],
            'original_name'  => ['type'=>'VARCHAR','constraint'=>255],
            'mime'           => ['type'=>'VARCHAR','constraint'=>80],
            'size'           => ['type'=>'BIGINT','null'=>true],
            'document_path'  => ['type'=>'VARCHAR','constraint'=>255], // ruta interna
            'status'         => ['type'=>'ENUM','constraint'=>['pending','signed','cancelled','error'],'default'=>'pending'],
            'param_token'    => ['type'=>'VARCHAR','constraint'=>64],   // un solo uso
            'expires_at'     => ['type'=>'DATETIME','null'=>true],
            'signed_path'    => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
            'created_at'     => ['type'=>'DATETIME','null'=>true],
            'updated_at'     => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['user_id','profile_id']);
        $this->forge->addKey('param_token');
        $this->forge->createTable('signature_jobs');
    }

    public function down()
    {
        $this->forge->dropTable('signature_jobs');
    }
}
