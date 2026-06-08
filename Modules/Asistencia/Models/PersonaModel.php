<?php

namespace Modules\Asistencia\Models;


use CodeIgniter\Model;

class PersonaModel extends Model
{
    protected $table            = 'casis_persona';
    protected $primaryKey       = 'per_ide';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields    = [
        'per_dni',
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
        'per_user',
        'per_pass'
    ];

    // Gestión de fechas
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'per_fecha_registro';
    protected $updatedField  = ''; // No definido en tu SQL, lo dejamos vacío

    // Reglas de validación orientadas a datos de personal
    protected $validationRules      = [
        'per_dni'    => 'permit_empty|exact_length[8]|numeric',
        'per_ruc'    => 'permit_empty|min_length[11]|max_length[15]|numeric',
        'per_email'  => 'permit_empty|valid_email',
        'per_nombre' => 'required|min_length[2]',
    ];

    protected $validationMessages   = [
        'per_dni' => [
            'exact_length' => 'El DNI debe tener exactamente 8 dígitos.',
            'numeric'      => 'El DNI solo debe contener números.'
        ],
        'per_email' => [
            'valid_email' => 'Por favor, ingrese un correo electrónico válido.'
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Obtener el nombre completo formateado
     * Útil para reportes de asistencia o listas desplegables
     */
    public function getNombreCompleto($id)
    {
        $persona = $this->find($id);
        if (!$persona) return null;

        return trim("{$persona['per_paterno']} {$persona['per_materno']}, {$persona['per_nombre']}");
    }
}
