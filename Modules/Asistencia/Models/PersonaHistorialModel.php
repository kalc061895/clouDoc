<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class PersonaHistorialModel extends Model
{
    protected $table            = 'casis_persona_historial';
    protected $primaryKey       = 'hist_per_ide';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    protected $allowedFields    = [
        'per_ide',
        'per_tdi_ide',
        'per_numero_documento',
        'per_paterno',
        'per_materno',
        'per_nombre',
        'per_foto',
        'per_sexo',
        'per_lugar_nacimiento',
        'per_fecha_nacimiento',
        'per_residencia',
        'per_ruc',
        'per_telefono',
        'per_email',
        'per_estadocivil',
        'per_ingreso',
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
        'per_ide'     => 'required|integer',
        'hist_accion' => 'required|in_list[INSERT,UPDATE,DELETE]',
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
