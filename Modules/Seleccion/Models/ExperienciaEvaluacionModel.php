<?php

namespace Modules\Seleccion\Models;

use CodeIgniter\Model;

class ExperienciaEvaluacionModel extends Model
{
    protected $table = 'selec_experiencia_evaluaciones';
    protected $primaryKey = 'exe_ide';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'exe_eva_ide',
        'exe_pex_ide',
        'exe_resultado',
        'exe_dias_declarados',
        'exe_dias_validados',
        'exe_puntaje',
        'exe_observacion',
        'created_by',
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';
}

