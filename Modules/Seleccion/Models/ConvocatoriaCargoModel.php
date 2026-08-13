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
}
