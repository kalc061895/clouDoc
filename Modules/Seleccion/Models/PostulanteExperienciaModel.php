<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class PostulanteExperienciaModel extends Model
{
    protected $table = 'selec_postulante_experiencias';
    protected $primaryKey = 'pex_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'pex_pos_ide',
        'pex_institucion',
        'pex_cargo',
        'pex_area',
        'pex_mvi_ide',
        'pex_fecha_inicio',
        'pex_fecha_termino',
        'pex_dias_declarados',
        'pex_descripcion',
        'pex_documento_ide',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}

