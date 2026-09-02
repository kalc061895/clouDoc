<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class RedModel extends Model
{
    protected $table = 'casis_red';
    protected $primaryKey = 'red_ide';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $allowedFields = [
        'red_dir_ide',
        'red_codigo',
        'red_nombre',
        'red_director',
        'red_ubicacion',
        'red_telefono',
        'red_email',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $validationRules = [
        'red_dir_ide' => 'required|integer',
        'red_nombre'  => 'required|max_length[150]',
        'red_email'   => 'permit_empty|valid_email',
    ];

    protected $validationMessages = [
        'red_dir_ide' => [
            'required' => 'Debe seleccionar una DIRESA.',
            'integer'  => 'El campo DIRESA debe ser un número entero.',
        ],
        'red_nombre' => [
            'required'   => 'Debe ingresar el nombre de la red de salud.',
            'max_length' => 'El nombre no puede exceder los 150 caracteres.',
        ],
        'red_email' => [
            'valid_email' => 'Debe ingresar un correo electrónico válido.',
        ],
    ];

    protected $skipValidation = false;
    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;
    protected array $casts = [];
    protected array $castHandlers = [];
    
}
