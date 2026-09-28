<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class TipoBonificacionModel extends Model
{
    protected $table = 'selec_tipos_bonificacion';
    protected $primaryKey = 'tbo_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'tbo_codigo',
        'tbo_nombre',
        'tbo_descripcion',
        'tbo_tipo_calculo',
        'tbo_estado',
    ];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
}

