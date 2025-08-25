<?php

// app/Database/Migrations/2025-08-24-000001_CreateSignatureProfiles.php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSignatureProfiles extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                   => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'user_id'              => ['type'=>'INT','constraint'=>11,'unsigned'=>true],
            'signatureFormat'      => ['type'=>'ENUM','constraint'=>['PAdES','XAdES','CAdES'],'default'=>'PAdES'],
            'signatureLevel'       => ['type'=>'ENUM','constraint'=>['B','T','LTA'],'default'=>'B'],
            'signaturePackaging'   => ['type'=>'VARCHAR','constraint'=>30,'default'=>'enveloped'],
            'certificateFilter'    => ['type'=>'VARCHAR','constraint'=>255,'default'=>'.*'],
            'theme'                => ['type'=>'VARCHAR','constraint'=>10,'default'=>'claro'],
            'visiblePosition'      => ['type'=>'TINYINT','constraint'=>1,'default'=>1],
            'contactInfo'          => ['type'=>'VARCHAR','constraint'=>120,'null'=>true],
            'signatureReason'      => ['type'=>'VARCHAR','constraint'=>200,'default'=>''],
            // ¡campo con la misma ortografía que la guía!
            'bachtOperation'       => ['type'=>'TINYINT','constraint'=>1,'default'=>0],
            'oneByOne'             => ['type'=>'TINYINT','constraint'=>1,'default'=>1],
            'signatureStyle'       => ['type'=>'TINYINT','constraint'=>1,'default'=>1],
            'imageToStamp'         => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
            'stampTextSize'        => ['type'=>'INT','default'=>14],
            'stampWordWrap'        => ['type'=>'INT','default'=>37],
            'role'                 => ['type'=>'VARCHAR','constraint'=>120,'default'=>''],
            'stampPage'            => ['type'=>'INT','default'=>1],
            'positionx'            => ['type'=>'INT','default'=>20],
            'positiony'            => ['type'=>'INT','default'=>20],
            'webTsa'               => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
            'userTsa'              => ['type'=>'VARCHAR','constraint'=>120,'null'=>true],
            'passwordTsa'          => ['type'=>'VARCHAR','constraint'=>120,'null'=>true],
            'certificationSignature'=>['type'=>'TINYINT','constraint'=>1,'default'=>0],
            'created_at'           => ['type'=>'DATETIME','null'=>true],
            'updated_at'           => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->createTable('signature_profiles');
    }

    public function down()
    {
        $this->forge->dropTable('signature_profiles');
    }
}
