<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class TipoDocumentoModel extends Model
{
    protected $table = 'selec_tipos_documento';
    protected $primaryKey = 'tdo_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'tdo_codigo',
        'tdo_nombre',
        'tdo_estado',
    ];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
}

