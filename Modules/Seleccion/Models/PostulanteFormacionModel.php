<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class PostulanteFormacionModel extends Model
{
    protected $table = 'selec_postulante_formacion';
    protected $primaryKey = 'pfo_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'pfo_pos_ide',
        'pfo_nfo_ide',
        'pfo_institucion',
        'pfo_carrera',
        'pfo_grado',
        'pfo_fecha_inicio',
        'pfo_fecha_culminacion',
        'pfo_fecha_obtencion',
        'pfo_documento_ide',
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
}

