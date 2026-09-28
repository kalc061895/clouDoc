<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class ConvocatoriaModel extends Model
{
    protected $table = 'selec_convocatorias';
    protected $primaryKey = 'con_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'con_codigo',
        'con_numero',
        'con_nombre',
        'con_tco_ide',
        'con_eco_ide',
        'con_regimen_laboral',
        'con_anio',
        'con_resolucion',
        'con_descripcion',
        'con_fecha_publicacion',
        'con_fecha_inicio',
        'con_fecha_cierre',
        'con_responsable_ide',
        'con_observacion',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $useSoftDeletes = true;
}

