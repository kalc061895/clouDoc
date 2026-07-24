<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class PersonalProfesionHistorialModel extends Model
{
    protected $table            = 'casis_personal_profesion_historial';
    protected $primaryKey       = 'hist_pp_ide';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    protected $allowedFields    = [
        'pp_ide',
        'pp_perl_ide',
        'pp_principal',
        'pp_pro_ide',
        'pp_col_ide',
        'pp_se_ide',
        'pp_numero_colegiatura',
        'pp_rne',
        'pp_habilitado',
        'pp_fecha_habilitacion',
        'pp_fecha_vencimiento',
        'pp_observacion',
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
        'pp_ide'      => 'required|integer',
        'pp_perl_ide' => 'required|integer',
        'pp_pro_ide'  => 'required|integer',
        'hist_accion' => 'required|in_list[INSERT,UPDATE,DELETE]',
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
