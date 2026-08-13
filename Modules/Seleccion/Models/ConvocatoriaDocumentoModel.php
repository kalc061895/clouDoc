<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class ConvocatoriaDocumentoModel extends Model
{
    protected $table = 'selec_convocatoria_documentos';
    protected $primaryKey = 'cod_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'cod_con_ide',
        'cod_nombre',
        'cod_descripcion',
        'cod_tipo',
        'cod_ruta',
        'cod_nombre_interno',
        'cod_mime',
        'cod_tamanio',
        'cod_hash',
        'cod_version',
        'cod_documento_padre_ide',
        'cod_motivo_version',
        'cod_obligatorio',
        'created_by',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
