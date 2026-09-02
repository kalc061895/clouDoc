<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class AdjuntoModel extends Model
{
    protected $table            = 'casis_adjuntos';
    protected $primaryKey       = 'adj_ide';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields    = [
        'adj_modulo',
        'adj_registro_id',
        'adj_local_path',
        'adj_drive_path',
        'adj_nombre_original',
        'adj_mime_type',
        'adj_tamano',
        'adj_orden',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $useSoftDeletes   = true;
    protected $deletedField     = 'deleted_at';

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = '';

    /**
     * Obtener los adjuntos de un registro específico
     */
    public function obtenerPorRegistro(string $modulo, int $registroId): array
    {
        return $this->where('adj_modulo', $modulo)
                    ->where('adj_registro_id', $registroId)
                    ->orderBy('adj_orden', 'ASC')
                    ->findAll();
    }
}