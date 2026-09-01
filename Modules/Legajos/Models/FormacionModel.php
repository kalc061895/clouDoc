<?php

namespace Modules\Legajos\Models;

use CodeIgniter\Model;

class FormacionModel extends Model
{
    protected $table            = 'leg_formacion_academica';
    protected $primaryKey       = 'for_ide';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    protected $allowedFields = [
        'for_ser_ide',
        'for_nivel_educativo',
        'for_institucion',
        'for_carrera_especialidad',
        'for_grado_obtenido',
        'for_fecha_expedicion',
        'for_colegio_profesional',
        'for_numero_colegiatura',
        'for_es_habilitado',
        'for_fecha_habilitacion_vigencia',
        'for_pais',
        'for_registro_sunedu',
        'for_adjunto_sustento',
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
        'for_ser_ide'              => 'required|is_natural_no_zero',
        'for_nivel_educativo'      => 'required',
        'for_institucion'          => 'required|min_length[3]|max_length[200]',
        'for_carrera_especialidad' => 'required|min_length[3]|max_length[200]',
    ];

    public function getPorServidor(int $servidorId): array
    {
        return $this->where('for_ser_ide', $servidorId)
            ->orderBy('for_fecha_expedicion', 'DESC')
            ->findAll();
    }
}

