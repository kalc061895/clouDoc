<?php

namespace Modules\Asistencia\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DiresaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'dir_nombre' => 'DIRECCION REGIONAL DE SALUD PUNO',
                'dir_director' => 'M.C. DIRECTOR DE TURNO PUNO',
                'dir_ubicacion' => 'PUNO',
                'dir_telefono' => '051-351031',
                'dir_email' => 'contactenos@diresapuno.gob.pe'
            ],
            [
                'dir_nombre' => 'DIRECCION REGIONAL DE SALUD AREQUIPA (GERESA)',
                'dir_director' => 'M.C. GERENTE DE TURNO AREQUIPA',
                'dir_ubicacion' => 'AREQUIPA',
                'dir_telefono' => '054-233481',
                'dir_email' => 'informes@geresarequipa.gob.pe'
            ],
            [
                'dir_nombre' => 'DIRECCION REGIONAL DE SALUD CUSCO (GERESA)',
                'dir_director' => 'M.C. GERENTE DE TURNO CUSCO',
                'dir_ubicacion' => 'CUSCO',
                'dir_telefono' => '084-221521',
                'dir_email' => 'contacto@geresacusco.gob.pe'
            ],
            [
                'dir_nombre' => 'DIRECCION REGIONAL DE SALUD TACNA',
                'dir_director' => 'M.C. DIRECTOR DE TURNO TACNA',
                'dir_ubicacion' => 'TACNA',
                'dir_telefono' => '052-424424',
                'dir_email' => 'webmaster@diresatacna.gob.pe'
            ],
            [
                'dir_nombre' => 'DIRECCION REGIONAL DE SALUD MOQUEGUA',
                'dir_director' => 'M.C. DIRECTOR DE TURNO MOQUEGUA',
                'dir_ubicacion' => 'MOQUEGUA',
                'dir_telefono' => '053-462196',
                'dir_email' => 'diresa@diresamoquegua.gob.pe'
            ],
            [
                'dir_nombre' => 'DIRECCION REGIONAL DE SALUD LIMA PROVINCIAS',
                'dir_director' => 'M.C. DIRECTOR DE TURNO LIMA',
                'dir_ubicacion' => 'HUACHO',
                'dir_telefono' => '01-2391219',
                'dir_email' => 'diresa@diresalima.gob.pe'
            ],
            [
                'dir_nombre' => 'DIRECCION REGIONAL DE SALUD CALLAO',
                'dir_director' => 'M.C. DIRECTOR DE TURNO CALLAO',
                'dir_ubicacion' => 'CALLAO',
                'dir_telefono' => '01-4290074',
                'dir_email' => 'infodiresa@diresacallao.gob.pe'
            ],
            [
                'dir_nombre' => 'DIRECCION REGIONAL DE SALUD JUNIN',
                'dir_director' => 'M.C. DIRECTOR DE TURNO JUNIN',
                'dir_ubicacion' => 'HYO - JUNIN',
                'dir_telefono' => '064-481410',
                'dir_email' => 'diresajunin@diresajunin.gob.pe'
            ],
            [
                'dir_nombre' => 'DIRECCION REGIONAL DE SALUD ICA',
                'dir_director' => 'M.C. DIRECTOR DE TURNO ICA',
                'dir_ubicacion' => 'ICA',
                'dir_telefono' => '056-228221',
                'dir_email' => 'diresaica@diresaica.gob.pe'
            ],
            [
                'dir_nombre' => 'DIRECCION REGIONAL DE SALUD AYACUCHO',
                'dir_director' => 'M.C. DIRECTOR DE TURNO AYACUCHO',
                'dir_ubicacion' => 'AYACUCHO',
                'dir_telefono' => '066-312521',
                'dir_email' => 'diresa@diresaayacucho.gob.pe'
            ],
            [
                'dir_nombre' => 'DIRECCION REGIONAL DE SALUD APURIMAC',
                'dir_director' => 'M.C. DIRECTOR DE TURNO APURIMAC',
                'dir_ubicacion' => 'ABANCAY',
                'dir_telefono' => '083-321151',
                'dir_email' => 'diresa@diresaapurimac.gob.pe'
            ],
            [
                'dir_nombre' => 'DIRECCION REGIONAL DE SALUD HUANCAVELICA',
                'dir_director' => 'M.C. DIRECTOR DE TURNO HUANCAVELICA',
                'dir_ubicacion' => 'HUANCAVELICA',
                'dir_telefono' => '067-451321',
                'dir_email' => 'diresa@diresahuancavelica.gob.pe'
            ],
            [
                'dir_nombre' => 'DIRECCION REGIONAL DE SALUD HUANUCO',
                'dir_director' => 'M.C. DIRECTOR DE TURNO HUANUCO',
                'dir_ubicacion' => 'HUANUCO',
                'dir_telefono' => '062-512521',
                'dir_email' => 'diresa@diresahuanuco.gob.pe'
            ],
            [
                'dir_nombre' => 'DIRECCION REGIONAL DE SALUD ANCASH',
                'dir_director' => 'M.C. DIRECTOR DE TURNO ANCASH',
                'dir_ubicacion' => 'HUARAZ',
                'dir_telefono' => '043-421521',
                'dir_email' => 'diresa@diresaancash.gob.pe'
            ],
            [
                'dir_nombre' => 'DIRECCION REGIONAL DE SALUD LA LIBERTAD (GERESA)',
                'dir_director' => 'M.C. GERENTE DE TURNO LA LIBERTAD',
                'dir_ubicacion' => 'TRUJILLO',
                'dir_telefono' => '044-232321',
                'dir_email' => 'diresa@geresalalibertad.gob.pe'
            ],
            [
                'dir_nombre' => 'DIRECCION REGIONAL DE SALUD LAMBAYEQUE (GERESA)',
                'dir_director' => 'M.C. GERENTE DE TURNO LAMBAYEQUE',
                'dir_ubicacion' => 'CHICLAYO',
                'dir_telefono' => '074-232521',
                'dir_email' => 'contacto@geresalambayeque.gob.pe'
            ],
            [
                'dir_nombre' => 'DIRECCION REGIONAL DE SALUD PIURA',
                'dir_director' => 'M.C. DIRECTOR DE TURNO PIURA',
                'dir_ubicacion' => 'PIURA',
                'dir_telefono' => '073-307521',
                'dir_email' => 'diresapiura@diresapiura.gob.pe'
            ],
            [
                'dir_nombre' => 'DIRECCION REGIONAL DE SALUD TUMBES',
                'dir_director' => 'M.C. DIRECTOR DE TURNO TUMBES',
                'dir_ubicacion' => 'TUMBES',
                'dir_telefono' => '072-523521',
                'dir_email' => 'diresa@diresatumbes.gob.pe'
            ],
            [
                'dir_nombre' => 'DIRECCION REGIONAL DE SALUD CAJAMARCA',
                'dir_director' => 'M.C. DIRECTOR DE TURNO CAJAMARCA',
                'dir_ubicacion' => 'CAJAMARCA',
                'dir_telefono' => '076-363521',
                'dir_email' => 'diresa@diresacajamarca.gob.pe'
            ],
            [
                'dir_nombre' => 'DIRECCION REGIONAL DE SALUD SAN MARTIN',
                'dir_director' => 'M.C. DIRECTOR DE TURNO SAN MARTIN',
                'dir_ubicacion' => 'MOYOBAMBA',
                'dir_telefono' => '042-562521',
                'dir_email' => 'diresa@diresasanmartin.gob.pe'
            ],
            [
                'dir_nombre' => 'DIRECCION REGIONAL DE SALUD AMAZONAS',
                'dir_director' => 'M.C. DIRECTOR DE TURNO AMAZONAS',
                'dir_ubicacion' => 'CHACHAPOYAS',
                'dir_telefono' => '041-477521',
                'dir_email' => 'diresa@diresaamazonas.gob.pe'
            ],
            [
                'dir_nombre' => 'DIRECCION REGIONAL DE SALUD LORETO',
                'dir_director' => 'M.C. DIRECTOR DE TURNO LORETO',
                'dir_ubicacion' => 'IQUITOS',
                'dir_telefono' => '065-223521',
                'dir_email' => 'diresaloreto@diresaloreto.gob.pe'
            ],
            [
                'dir_nombre' => 'DIRECCION REGIONAL DE SALUD UCAYALI',
                'dir_director' => 'M.C. DIRECTOR DE TURNO UCAYALI',
                'dir_ubicacion' => 'PUCALLPA',
                'dir_telefono' => '061-572521',
                'dir_email' => 'diresa@diresaucayali.gob.pe'
            ],
            [
                'dir_nombre' => 'DIRECCION REGIONAL DE SALUD MADRE DE DIOS',
                'dir_director' => 'M.C. DIRECTOR DE TURNO MADRE DE DIOS',
                'dir_ubicacion' => 'PUERTO MALDONADO',
                'dir_telefono' => '082-571521',
                'dir_email' => 'diresa@diresamadrededios.gob.pe'
            ],
            [
                'dir_nombre' => 'DIRECCION REGIONAL DE SALUD PASCO',
                'dir_director' => 'M.C. DIRECTOR DE TURNO PASCO',
                'dir_ubicacion' => 'CERRO DE PASCO',
                'dir_telefono' => '063-421521',
                'dir_email' => 'diresa@diresapasco.gob.pe'
            ]
        ];

        $this->db->table('casis_diresa')->insertBatch($data);
    }
}
