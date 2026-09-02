<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class TipoOficinaModel extends Model
{
    protected $table            = 'casis_tipo_oficina';
    protected $primaryKey       = 'tofi_ide';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $protectFields = true;

    protected $allowedFields = [
        'tofi_codigo',
        'tofi_nombre',
        'tofi_descripcion',
        'tofi_estado',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'tofi_nombre' => 'required|max_length[100]',
    ];

    public function activos()
    {
        return $this->where('tofi_estado', 1)
            ->orderBy('tofi_nombre')
            ->findAll();
    }
}
