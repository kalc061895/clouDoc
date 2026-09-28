<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class ExpedienteDocumentoModel extends Model
{
    protected $table = 'selec_expediente_documentos';
    protected $primaryKey = 'exd_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'exd_pto_ide',
        'exd_tipo_documento',
        'exd_nombre_original',
        'exd_nombre_interno',
        'exd_ruta',
        'exd_mime',
        'exd_tamanio',
        'exd_hash',
        'exd_version',
        'exd_estado',
        'created_by',
        'exd_fecha_carga',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
}

