<?php

namespace Modules\Seleccion\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MasterSeeder extends Seeder
{
    public function run()
    {
        $this->call(EtapaSeeder::class);
    }
}
