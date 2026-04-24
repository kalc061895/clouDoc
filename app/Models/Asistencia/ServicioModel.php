<?php

namespace App\Models\Asistencia;

use CodeIgniter\Model;

class ServicioModel extends Model
{
    protected $table            = 'casis_servicio';
    protected $primaryKey       = 'ser_ide';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    // Campos que se pueden llenar mediante los métodos save(), insert() o update()
    protected $allowedFields    = [
        'ser_abreviatura',
        'ser_nombre',
        'ser_departamento',
        'ser_descripcion',
        'ser_upss'
    ];

    // Esta tabla no parece tener campos de auditoría (timestamps) en el SQL proporcionado
    protected $useTimestamps = false;

    // Reglas de validación sugeridas para mantener la integridad en el sector salud
    protected $validationRules      = [
        'ser_abreviatura'  => 'required|max_length[50]',
        'ser_nombre'       => 'permit_empty|max_length[100]',
        'ser_departamento' => 'permit_empty|max_length[100]',
        'ser_upss'         => 'permit_empty|integer'
    ];

    protected $validationMessages   = [
        'ser_abreviatura' => [
            'required' => 'La abreviatura del servicio es obligatoria.'
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
