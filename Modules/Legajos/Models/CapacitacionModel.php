<?php

namespace Modules\Legajos\Models;

use CodeIgniter\Model;

class CapacitacionModel extends Model
{
    protected $table            = 'leg_evaluaciones_capacitaciones';
    protected $primaryKey       = 'evc_ide';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    protected $allowedFields = [
        'evc_ser_ide',
        'evc_tipo',
        'evc_titulo',
        'evc_institucion_organizadora',
        'evc_tipo_evento',
        'evc_fecha_inicio',
        'evc_fecha_fin',
        'evc_horas_academicas',
        'evc_creditos',
        'evc_calificacion_obtenida',
        'evc_es_financiado_entidad',
        'evc_adjunto_sustento',
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
        'evc_ser_ide'                  => 'required|is_natural_no_zero',
        'evc_tipo'                     => 'required',
        'evc_titulo'                   => 'required|min_length[3]|max_length[255]',
        'evc_institucion_organizadora' => 'required|min_length[3]|max_length[200]',
        'evc_fecha_inicio'             => 'required|valid_date',
    ];

    public function getPorServidor(int $servidorId): array
    {
        return $this->where('evc_ser_ide', $servidorId)
            ->orderBy('evc_fecha_inicio', 'DESC')
            ->findAll();
    }
}

