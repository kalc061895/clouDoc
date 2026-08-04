<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class PersonalHistorialModel extends Model
{
    protected $table            = 'casis_personal_historial';
    protected $primaryKey       = 'hist_perl_ide';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    protected $allowedFields    = [
        'perl_ide',
        'perl_per_ide',
        'perl_est_ide',
        'perl_ofi_ide',
        'perl_car_ide',
        'perl_mco_ide',
        'perl_se_ide',
        'perl_codigo',
        'perl_fecha_inicio',
        'perl_fecha_termino',
        'perl_fecha_cese',
        'perl_numero_colegiatura',
        'perl_plaza',
        'perl_nivel',
        'perl_estado',
        'perl_observacion',
        'hist_accion',
        'hist_hecho_por',
        'hist_creado_en',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // Configuración de Fechas
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Reglas de validación básicas
    protected $validationRules = [
        'perl_ide'     => 'required|integer',
        'perl_per_ide' => 'required|integer',
        'hist_accion'  => 'required|in_list[INSERT,UPDATE,DELETE]',
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
