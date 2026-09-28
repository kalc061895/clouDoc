<?php

namespace Modules\Asistencia\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MasterSeeder extends Seeder
{
    public function run()
    {
        // 1. Tablas independientes (Padres absolutos)
        $this->call('Modules\Asistencia\Database\Seeds\DiresaSeeder');
        $this->call('Modules\Asistencia\Database\Seeds\RedSeeder');
        $this->call('Modules\Asistencia\Database\Seeds\MicroredSeeder'); // Depende de Red/Diresa
        $this->call('Modules\Asistencia\Database\Seeds\EstablecimientoSeeder'); // Depende de Microred

        // 2. Tablas de configuración de recursos humanos y categorización
        $this->call('Modules\Asistencia\Database\Seeds\ProfesionSeeder');
        $this->call('Modules\Asistencia\Database\Seeds\CargoSeeder');
        $this->call('Modules\Asistencia\Database\Seeds\ColegiaturaSeeder');
        $this->call('Modules\Asistencia\Database\Seeds\TipoDocumentoSeeder');
        $this->call('Modules\Asistencia\Database\Seeds\ModalidadContratoSeeder');

        // 3. Tablas de estructura organizacional
        $this->call('Modules\Asistencia\Database\Seeds\TipoOficinaSeeder');
        $this->call('Modules\Asistencia\Database\Seeds\OficinaSeeder'); // Depende de TipoOficina
        $this->call('Modules\Asistencia\Database\Seeds\UpssSeeder'); // Depende de Establecimiento

        // 4. Tablas de catálogos específicos (Tu reciente SegundaEspecialidad)
        $this->call('Modules\Asistencia\Database\Seeds\SegundaEspecialidadSeeder');

        // 5. Tablas de reglas de negocio y control
        $this->call('Modules\Asistencia\Database\Seeds\DiaFeriadoSeeder');
        $this->call('Modules\Asistencia\Database\Seeds\LicenciaSeeder');
        $this->call('Modules\Asistencia\Database\Seeds\PermisoSeeder');

        // 6. Finalmente, menús y permisos de sistema
        $this->call('Modules\Asistencia\Database\Seeds\MenuSeeder');
    }
}
