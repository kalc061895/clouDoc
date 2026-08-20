<?php

namespace Modules\Seleccion\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MasterSeeder extends Seeder
{
    public function run()
    {
        $this->call(EtapaSeeder::class);
        $this->call(DatosGeneralesSeeder::class);
        $this->call(MenuSeeder::class);
        $this->call(MenuGroupUserSeeder::class);
    }
}
