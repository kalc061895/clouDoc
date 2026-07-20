<?php

namespace Modules\Asistencia\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MasterSeeder extends Seeder
{
    public function run()
    {
        // 1. Tablas independientes (Padres absolutos)
        $this->call('DiresaSeeder');
        $this->call('RedSeeder');
        $this->call('MicroredSeeder'); // Depende de Red/Diresa

        // 2. Tablas de configuración de recursos humanos y categorización
        $this->call('ProfesionSeeder');
        $this->call('CargoSeeder');
        $this->call('ColegiaturaSeeder');
        $this->call('TipoDocumentoSeeder');
        $this->call('ModalidadContratoSeeder');

        // 3. Tablas de estructura organizacional
        $this->call('TipoOficinaSeeder');
        $this->call('OficinaSeeder'); // Depende de TipoOficina
        $this->call('UnidadesOrganizacionalesSeeder');
        $this->call('EstablecimientoSeeder'); // Depende de Microred
        $this->call('UpssSeeder'); // Depende de Establecimiento

        // 4. Tablas de catálogos específicos (Tu reciente SegundaEspecialidad)
        $this->call('SegundaEspecialidadSeeder');

        // 5. Tablas de reglas de negocio y control
        $this->call('DiaFeriadoSeeder');
        $this->call('LicenciaSeeder');
        $this->call('PermisoSeeder');

        // 6. Finalmente, menús y permisos de sistema
        $this->call('MenuSeeder');
    }
}
