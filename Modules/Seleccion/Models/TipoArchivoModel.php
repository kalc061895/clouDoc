<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class TipoArchivoModel extends Model
{
    protected $table = 'selec_tipos_archivo';
    protected $primaryKey = 'tar_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'tar_codigo',
        'tar_nombre',
        'tar_extension',
        'tar_mime',
        'tar_estado',
    ];
}

