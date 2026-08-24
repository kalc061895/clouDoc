<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class CargoModel extends Model
{
    protected $table = 'selec_cargos';
    protected $primaryKey = 'car_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'car_codigo',
        'car_denominacion',
        'car_especialidad',
        'car_tca_ide',
        'car_gru_ide',
        'car_niv_ide',
        'car_pro_ide',
        'car_descripcion',
        'car_estado',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $useSoftDeletes = true;

}
