<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class ReclamoDocumentoModel extends Model
{
    protected $table = 'selec_reclamo_documentos';
    protected $primaryKey = 'red_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'red_rec_ide',
        'red_exd_ide',
        'red_descripcion',
    ];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
}

