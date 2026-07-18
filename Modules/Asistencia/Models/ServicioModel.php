<?php
namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class ServicioModel extends Model
{
    protected $table = 'casis_servicio';

    protected $primaryKey = 'ser_ide';

    protected $returnType = 'array';

    protected $useAutoIncrement = true;

    protected $protectFields = true;

    protected $allowedFields = [

        'ser_abreviatura',
        'ser_nombre',
        'ser_departamento',
        'ser_descripcion',
        'ser_upss',

        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /*
    |--------------------------------------------------------------------------
    | Soft Delete
    |--------------------------------------------------------------------------
    */

    protected $useSoftDeletes = true;

    protected $deletedField = 'deleted_at';

    /*
    |--------------------------------------------------------------------------
    | Timestamps
    |--------------------------------------------------------------------------
    */

    protected $useTimestamps = true;

    protected $dateFormat = 'datetime';

    protected $createdField = 'created_at';

    protected $updatedField = 'updated_at';

    /*
    |--------------------------------------------------------------------------
    | Callbacks
    |--------------------------------------------------------------------------
    */

    protected $beforeInsert = ['beforeInsert'];

    protected $beforeUpdate = ['beforeUpdate'];

    protected $beforeDelete = ['beforeDelete'];

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    protected $validationRules = [

        'ser_abreviatura' => 'required|max_length[50]',
        'ser_nombre' => 'permit_empty|max_length[100]',
        'ser_departamento' => 'permit_empty|max_length[100]',
        'ser_upss' => 'permit_empty|integer',
    ];

    /**
     * Antes de insertar
     */
    protected function beforeInsert(array $data)
    {
        if (auth()->loggedIn()) {

            $data['data']['created_by'] = auth()->id();
        }

        return $data;
    }

    /**
     * Antes de actualizar
     */
    protected function beforeUpdate(array $data)
    {
        if (auth()->loggedIn()) {

            $data['data']['updated_by'] = auth()->id();
        }

        return $data;
    }

    /**
     * Antes de eliminar
     */
    protected function beforeDelete(array $data)
    {
        if (! auth()->loggedIn()) {
            return $data;
        }

        if (! empty($data['id'])) {

            $this->builder()
                ->whereIn($this->primaryKey, (array) $data['id'])
                ->update([
                    'deleted_by' => auth()->id()
                ]);
        }

        return $data;
    }
}