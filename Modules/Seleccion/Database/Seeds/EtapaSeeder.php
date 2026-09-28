<?php

namespace Modules\Seleccion\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EtapaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['eta_codigo' => 'PUBLICACION', 'eta_nombre' => 'Publicación', 'eta_descripcion' => 'Publicación de la convocatoria', 'eta_orden' => 1, 'eta_estado' => 'ACTIVO'],
            ['eta_codigo' => 'INSCRIPCION', 'eta_nombre' => 'Inscripción', 'eta_descripcion' => 'Registro de postulaciones', 'eta_orden' => 2, 'eta_estado' => 'ACTIVO'],
            ['eta_codigo' => 'EVALUACION_CURRICULAR', 'eta_nombre' => 'Evaluación curricular', 'eta_descripcion' => 'Evaluación curricular', 'eta_orden' => 3, 'eta_estado' => 'ACTIVO'],
            ['eta_codigo' => 'RESULTADOS_PRELIMINARES', 'eta_nombre' => 'Resultados preliminares', 'eta_descripcion' => 'Publicación de resultados preliminares', 'eta_orden' => 4, 'eta_estado' => 'ACTIVO'],
            ['eta_codigo' => 'RECLAMOS', 'eta_nombre' => 'Reclamos', 'eta_descripcion' => 'Presentación de reclamos', 'eta_orden' => 5, 'eta_estado' => 'ACTIVO'],
            ['eta_codigo' => 'EVALUACION_RECLAMOS', 'eta_nombre' => 'Evaluación de reclamos', 'eta_descripcion' => 'Evaluación de reclamos', 'eta_orden' => 6, 'eta_estado' => 'ACTIVO'],
            ['eta_codigo' => 'RESULTADOS_POST_RECLAMOS', 'eta_nombre' => 'Resultados post reclamos', 'eta_descripcion' => 'Publicación de resultados posteriores a reclamos', 'eta_orden' => 7, 'eta_estado' => 'ACTIVO'],
            ['eta_codigo' => 'ENTREVISTA', 'eta_nombre' => 'Entrevista', 'eta_descripcion' => 'Entrevista personal', 'eta_orden' => 8, 'eta_estado' => 'ACTIVO'],
            ['eta_codigo' => 'BONIFICACIONES', 'eta_nombre' => 'Bonificaciones', 'eta_descripcion' => 'Evaluación de bonificaciones', 'eta_orden' => 9, 'eta_estado' => 'ACTIVO'],
            ['eta_codigo' => 'RESULTADOS_FINALES', 'eta_nombre' => 'Resultados finales', 'eta_descripcion' => 'Publicación de resultados finales', 'eta_orden' => 10, 'eta_estado' => 'ACTIVO'],
        ];

        foreach ($data as $row) {
            if ($this->db->table('selec_etapas')->where('eta_codigo', $row['eta_codigo'])->countAllResults() === 0) {
                $this->db->table('selec_etapas')->insert($row);
            }
        }
    }
}
