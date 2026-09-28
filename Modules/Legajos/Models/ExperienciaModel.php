<?php

namespace Modules\Legajos\Models;

use CodeIgniter\Model;

class ExperienciaModel extends Model
{
    protected $table            = 'leg_experiencia_laboral';
    protected $primaryKey       = 'exp_ide';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    protected $allowedFields = [
        'exp_ser_ide',
        'exp_tipo_entidad',
        'exp_entidad_empresa',
        'exp_cargo_desempenado',
        'exp_unidad_organica',
        'exp_fecha_inicio',
        'exp_fecha_fin',
        'exp_tiempo_anios',
        'exp_tiempo_meses',
        'exp_tiempo_dias',
        'exp_funciones_principales',
        'exp_motivo_cese',
        'exp_adjunto_sustento',
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
        'exp_ser_ide'           => 'required|is_natural_no_zero',
        'exp_tipo_entidad'      => 'required|in_list[PUBLICA,PRIVADA]',
        'exp_entidad_empresa'   => 'required|min_length[3]|max_length[200]',
        'exp_cargo_desempenado' => 'required|min_length[3]|max_length[150]',
        'exp_fecha_inicio'      => 'required|valid_date',
    ];

    public function getPorServidor(int $servidorId): array
    {
        return $this->where('exp_ser_ide', $servidorId)
            ->orderBy('exp_fecha_inicio', 'DESC')
            ->findAll();
    }
}

