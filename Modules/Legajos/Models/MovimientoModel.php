<?php

namespace Modules\Legajos\Models;

use CodeIgniter\Model;

class MovimientoModel extends Model
{
    protected $table            = 'leg_movimientos_personal';
    protected $primaryKey       = 'mov_ide';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    protected $allowedFields = [
        'mov_ser_ide',
        'mov_tipo',
        'mov_tipo_documento_sustento',
        'mov_numero_documento',
        'mov_fecha_documento',
        'mov_dependencia_origen',
        'mov_dependencia_destino',
        'mov_cargo_destino',
        'mov_fecha_inicio',
        'mov_fecha_fin',
        'mov_dias_computados',
        'mov_motivo_detalle',
        'mov_adjunto_sustento',
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
        'mov_ser_ide'          => 'required|is_natural_no_zero',
        'mov_tipo'             => 'required',
        'mov_numero_documento' => 'required|min_length[3]|max_length[100]',
        'mov_fecha_inicio'     => 'required|valid_date',
    ];

    public function getPorServidor(int $servidorId): array
    {
        return $this->where('mov_ser_ide', $servidorId)
            ->orderBy('mov_fecha_inicio', 'DESC')
            ->findAll();
    }
}

