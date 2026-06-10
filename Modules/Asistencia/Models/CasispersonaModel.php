<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class CasispersonasModel extends Model
{
    protected $table            = 'casis_persona';
    protected $primaryKey       = 'per_ide';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields = [
        'per_tipo_documento',
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
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    // Validaciones
    protected $validationRules = [
        'per_tipo_documento' => 'required|max_length[20]',
        'per_numero_documento' => 'required|max_length[20]|is_unique[casis_persona.per_numero_documento,per_ide,{per_ide}]',
        'per_nombre' => 'required|max_length[100]',
    ];

    protected $validationMessages = [
        'per_numero_documento' => [
            'is_unique' => 'El número de documento ya se encuentra registrado.'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];


    public function obtenerPorDocumento(string $numeroDocumento)
    {
        return $this->where('per_numero_documento', $numeroDocumento)
            ->first();
    }
    public function obtenerNombreCompleto(int $perIde)
    {
        $persona = $this->find($perIde);

        if (!$persona) {
            return null;
        }

        return trim(
            $persona['per_paterno'] . ' ' .
                $persona['per_materno'] . ', ' .
                $persona['per_nombre']
        );
    }
}
