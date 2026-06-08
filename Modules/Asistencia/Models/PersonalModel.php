<?php

namespace Modules\Asistencia\Models;


use CodeIgniter\Model;

class PersonalModel extends Model
{
    protected $table            = 'casis_personal';
    protected $primaryKey       = 'perl_ide';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields    = [
        'perl_per_dni',
        'perl_codigo',
        'perl_tipo_contrato',
        'perl_fecha_inicio',
        'perl_esp_ide',
        'perl_ser_ide',
        'perl_estado',
        'perl_est_ide',
        'perl_nivel',
        'perl_plaza',
        'perl_segunda_especialidad',
        'perl_fecha_termino',
        'perl_observacion',
        'perl_tipo_colegio',
        'perl_numero_colegio',
        'perl_regimen_laboral'
    ];

    // Gestión de auditoría
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'perl_fecha_registro';
    protected $updatedField  = ''; // No definido en el SQL proporcionado

    // Reglas de validación
    protected $validationRules      = [
        'perl_per_dni'        => 'required|exact_length[8]',
        'perl_ser_ide'        => 'permit_empty|integer',
        'perl_esp_ide'        => 'permit_empty|integer',
        'perl_numero_colegio' => 'permit_empty|alpha_numeric_punct',
    ];

    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Obtiene los datos del personal junto con su información básica de persona
     * Útil para el control de asistencia por DNI
     */
    public function getPersonalDetallado($dni = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('casis_personal.*, casis_persona.per_paterno, casis_persona.per_materno, casis_persona.per_nombre, casis_servicio.ser_nombre');
        $builder->join('casis_persona', 'casis_persona.per_dni = casis_personal.perl_per_dni');
        $builder->join('casis_servicio', 'casis_servicio.ser_ide = casis_personal.perl_ser_ide', 'left');

        if ($dni) {
            $builder->where('perl_per_dni', $dni);
        }

        return $builder->get()->getResultArray();
    }
}
