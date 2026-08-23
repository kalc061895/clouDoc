<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class ComisionModel extends Model
{
    protected $table = 'selec_comisiones';
    protected $primaryKey = 'com_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'com_con_ide',
        'com_numero',
        'com_fecha_designacion',
        'com_documento_ide',
        'com_estado',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';
}

