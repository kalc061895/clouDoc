<?php

namespace Modules\Legajos\Models;

use CodeIgniter\Model;

class LegajoSeccionModel extends Model
{
    protected $table            = 'leg_secciones';
    protected $primaryKey       = 'sec_ide';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;

    protected $allowedFields = [
        'sec_numero',
        'sec_codigo',
        'sec_nombre',
        'sec_descripcion',
        'sec_icono',
        'sec_orden',
        'sec_estado',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    /**
     * Retorna todas las secciones activas ordenadas
     */
    public function getSeccionesActivas(): array
    {
        return $this->where('sec_estado', 1)
            ->orderBy('sec_orden', 'ASC')
            ->findAll();
    }
}

