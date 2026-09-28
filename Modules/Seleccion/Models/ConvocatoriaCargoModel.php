<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class ConvocatoriaCargoModel extends Model
{
    protected $table = 'selec_convocatoria_cargos';
    protected $primaryKey = 'cco_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'cco_con_ide',
        'cco_car_ide',
        'cco_numero_plazas',
        'cco_dependencia',
        'cco_area',
        'cco_establecimiento',
        'cco_remuneracion',
        'cco_observacion',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $useSoftDeletes = true;

    /**
     * Obtiene los cargos de una convocatoria uniendo la información maestra de cargos.
     */
    public function getCargosPorConvocatoria(int $convocatoriaId): array
    {
        return $this->select('
                selec_convocatoria_cargos.*,
                c.car_codigo,
                c.car_denominacion,
                c.car_especialidad,
                g.gru_nombre AS grupo_ocupacional
            ')
            ->join('selec_cargos c', 'c.car_ide = selec_convocatoria_cargos.cco_car_ide')
            ->join('selec_grupos_ocupacionales g', 'g.gru_ide = c.car_gru_ide', 'left')
            ->where('cco_con_ide', $convocatoriaId)
            ->findAll();
    }
}
