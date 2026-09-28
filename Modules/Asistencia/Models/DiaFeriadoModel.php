<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class DiaFeriadoModel extends Model
{
    protected $table            = 'casis_diaferiado';
    protected $primaryKey       = 'df_ide';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';

    protected $protectFields = true;

    protected $allowedFields = [
        'df_nombre',
        'df_fecha',
        'df_tipo',
        'df_repetitivo',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // Auditoría automática
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';

    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Soft Delete
    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';

    // Validaciones
    protected $validationRules = [
        'df_nombre' => 'required|max_length[100]',
        'df_fecha'  => 'required|valid_date',
    ];

    protected $validationMessages = [
        'df_nombre' => [
            'required' => 'Debe ingresar el nombre del feriado.',
        ],
        'df_fecha' => [
            'required' => 'Debe ingresar la fecha del feriado.',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Obtiene un feriado por fecha.
     */
    public function obtenerPorFecha(string $fecha)
    {
        return $this->where('df_fecha', $fecha)->first();
    }

    /**
     * Verifica si una fecha es feriado.
     */
    public function esFeriado(string $fecha): bool
    {
        return $this->where('df_fecha', $fecha)
            ->countAllResults() > 0;
    }

    /**
     * Obtiene los feriados de un año.
     */
    public function obtenerPorAnio(int $anio)
    {
        return $this->where('df_fecha >=', $anio . '-01-01')
            ->where('df_fecha <=', $anio . '-12-31')
            ->orderBy('df_fecha', 'ASC')
            ->findAll();
    }

    /**
     * Obtiene los feriados vigentes.
     */
    public function listar()
    {
        return $this->orderBy('df_fecha', 'ASC')
            ->findAll();
    }
}
