<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class MicroRedModel extends Model
{

    protected $table = 'casis_microred';
    protected $primaryKey = 'mic_ide';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $allowedFields = [
        'mic_red_ide',
        'mic_codigo',
        'mic_nombre',
        'mic_director',
        'mic_ubicacion',
        'mic_telefono',
        'mic_email',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $validationRules = [
        'mic_red_ide' => 'required|integer',
        'mic_nombre'  => 'required|max_length[150]',
        'mic_email'   => 'permit_empty|valid_email',
    ];

    public function obtenerPorRed(int $redIde)
    {
        return $this->where('mic_red_ide', $redIde)
            ->orderBy('mic_nombre')
            ->findAll();
    }
    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    protected $dateFormat    = 'datetime';
}
