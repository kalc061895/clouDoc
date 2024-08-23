<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MasterSeeder extends Seeder
{
    public function run()
    {
        // Call each individual seeder
        $this->call('AniosSeeder');
        $this->call('DiasSeeder');
        $this->call('MesesSeeder');
        $this->call('EmpresaConfiguracionSeeder');
        $this->call('GroupUserSeeder');
        $this->call('MenuSeeder');
        $this->call('MenuGroupUserSeeder');
        $this->call('OficinasSeeder');
        $this->call('TipoDocumentoSeeder');
        $this->call('TipoExpedienteSeeder');
        $this->call('UserDefaultSeeder');
        // Add more seeders as needed

    }
}
