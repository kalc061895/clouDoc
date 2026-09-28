<?php

namespace Modules\Legajos\Models;

use CodeIgniter\Model;

class ServidorModel extends Model
{
    protected $table            = 'leg_servidores';
    protected $primaryKey       = 'ser_ide';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    protected $allowedFields = [
        'ser_tipo_documento',
        'ser_numero_documento',
        'ser_ruc',
        'ser_nombres',
        'ser_apellido_paterno',
        'ser_apellido_materno',
        'ser_sexo',
        'ser_fecha_nacimiento',
        'ser_estado_civil',
        'ser_grupo_sanguineo',
        'ser_celular',
        'ser_telefono_fijo',
        'ser_email_institucional',
        'ser_email_personal',
        'ser_direccion',
        'ser_ubigeo',
        'ser_foto',
        'ser_regimen_laboral',
        'ser_condicion_laboral',
        'ser_cargo',
        'ser_dependencia',
        'ser_fecha_ingreso',
        'ser_fecha_cese',
        'ser_numero_legajo',
        'ser_estado',
        'ser_observaciones',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'ser_numero_documento' => 'required|min_length[8]|max_length[20]',
        'ser_nombres'          => 'required|min_length[2]|max_length[100]',
        'ser_apellido_paterno' => 'required|min_length[2]|max_length[100]',
        'ser_cargo'            => 'required|min_length[2]|max_length[150]',
        'ser_dependencia'      => 'required|min_length[2]|max_length[150]',
    ];

    protected $validationMessages = [
        'ser_numero_documento' => [
            'required' => 'El número de documento es obligatorio.',
        ],
        'ser_nombres' => [
            'required' => 'Los nombres son obligatorios.',
        ],
        'ser_apellido_paterno' => [
            'required' => 'El apellido paterno es obligatorio.',
        ],
        'ser_cargo' => [
            'required' => 'El cargo es obligatorio.',
        ],
        'ser_dependencia' => [
            'required' => 'La dependencia/oficina es obligatoria.',
        ],
    ];

    /**
     * Obtiene el nombre completo del servidor
     */
    public function getNombreCompleto(array $servidor): string
    {
        return trim("{$servidor['ser_apellido_paterno']} {$servidor['ser_apellido_materno']} {$servidor['ser_nombres']}");
    }

    /**
     * Obtiene estadísticas generales de servidores
     */
    public function getEstadisticas(): array
    {
        $total    = $this->countAllResults(false);
        $activos  = $this->where('ser_estado', 'ACTIVO')->countAllResults(false);
        $cesados  = $this->where('ser_estado', 'CESADO')->countAllResults(false);
        $licencia = $this->where('ser_estado', 'LICENCIA')->countAllResults(false);

        $porRegimen = $this->select('ser_regimen_laboral, COUNT(*) as cantidad')
            ->groupBy('ser_regimen_laboral')
            ->findAll();

        return [
            'total'       => $total,
            'activos'     => $activos,
            'cesados'     => $cesados,
            'licencia'    => $licencia,
            'por_regimen' => $porRegimen,
        ];
    }
}

