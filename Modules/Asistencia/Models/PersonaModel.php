<?php

namespace Modules\Asistencia\Models;

use CodeIgniter\Model;

class PersonaModel extends Model
{
    protected $table            = 'casis_persona';
    protected $primaryKey       = 'per_ide';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $protectFields    = true;

    /*
    |--------------------------------------------------------------------------
    | Campos permitidos
    |--------------------------------------------------------------------------
    */
    protected $allowedFields = [

        'per_tdi_ide',
        'per_numero_documento',
        'per_paterno',
        'per_materno',
        'per_nombre',
        'per_foto',
        'per_sexo',
        'per_lugar_nacimiento',
        'per_fecha_nacimiento',
        'per_residencia',
        'per_ruc',
        'per_telefono',
        'per_email',
        'per_estadocivil',
        'per_ingreso',

        // Auditoría
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /*
    |--------------------------------------------------------------------------
    | Soft Deletes
    |--------------------------------------------------------------------------
    */
    protected $useSoftDeletes = true;
    protected $deletedField   = 'deleted_at';

    /*
    |--------------------------------------------------------------------------
    | Timestamps
    |--------------------------------------------------------------------------
    */
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';

    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /*
    |--------------------------------------------------------------------------
    | Callbacks
    |--------------------------------------------------------------------------
    */
    protected $beforeInsert = ['setCreatedBy'];
    protected $beforeUpdate = ['setUpdatedBy'];

    /*
    |--------------------------------------------------------------------------
    | Validaciones
    |--------------------------------------------------------------------------
    */
    protected $validationRules = [

        'per_tdi_ide' =>
        'required|max_length[5]',

        'per_numero_documento' =>
        'required|numeric|min_length[8]|max_length[15]|is_unique[casis_persona.per_numero_documento,per_ide,{per_ide}]',

        'per_paterno' =>
        'required|max_length[100]',

        'per_materno' =>
        'required|max_length[100]',

        'per_nombre' =>
        'required|min_length[2]|max_length[150]',

        'per_ruc' =>
        'permit_empty|exact_length[11]|numeric',

        'per_email' =>
        'permit_empty|valid_email',

        'per_telefono' =>
        'permit_empty|max_length[20]',
    ];

    protected $validationMessages = [

        'per_numero_documento' => [
            'required'  => 'Debe ingresar el número de documento.',
            'numeric'   => 'El número de documento solo puede contener números.',
            'is_unique' => 'Ya existe una persona registrada con ese documento.'
        ],

        'per_nombre' => [
            'required' => 'Debe ingresar el nombre.'
        ],

        'per_email' => [
            'valid_email' => 'Debe ingresar un correo válido.'
        ],

        'per_ruc' => [
            'exact_length' => 'El RUC debe tener exactamente 11 dígitos.'
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /*
    |--------------------------------------------------------------------------
    | Auditoría
    |--------------------------------------------------------------------------
    */

    protected function setCreatedBy(array $data)
    {
        if (function_exists('auth') && auth()->loggedIn()) {
            $data['data']['created_by'] = auth()->id();
        }

        return $data;
    }

    protected function setUpdatedBy(array $data)
    {
        if (function_exists('auth') && auth()->loggedIn()) {
            $data['data']['updated_by'] = auth()->id();
        }

        return $data;
    }

    protected function setDeletedBy(array $data)
    {
        if (! function_exists('auth')) {
            return $data;
        }

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

    /*
    |--------------------------------------------------------------------------
    | Métodos auxiliares
    |--------------------------------------------------------------------------
    */

    public function getNombreCompleto(int $id): ?string
    {
        $persona = $this->find($id);

        if (!$persona) {
            return null;
        }

        return trim(sprintf(
            '%s %s, %s',
            $persona['per_paterno'],
            $persona['per_materno'],
            $persona['per_nombre']
        ));
    }

    public function getNombreCompletoArray(array $persona): string
    {
        return trim(sprintf(
            '%s %s, %s',
            $persona['per_paterno'] ?? '',
            $persona['per_materno'] ?? '',
            $persona['per_nombre'] ?? ''
        ));
    }

    // ... Tus propiedades anteriores se mantienen intactas ...

    protected $afterInsert = ['guardarHistorialInsert'];
    protected $afterUpdate = ['guardarHistorialUpdate'];
    protected $beforeDelete = ['guardarHistorialDelete','setDeletedBy']; // Se ejecuta antes de aplicar el soft-delete para congelar la fila completa

    protected function guardarHistorialInsert(array $data)
    {
        if (!$data['result']) return $data;
        
        $db = \Config\Database::connect();
        $registro = $this->find($data['id']);
        
        if ($registro) {
            $registro['hist_accion']    = 'INSERT';
            $registro['hist_hecho_por'] = (function_exists('auth') && auth()->loggedIn()) ? auth()->id() : null;
            $registro['hist_creado_en'] = date('Y-m-d H:i:s');
            
            $db->table('casis_persona_historial')->insert($registro);
        }
        return $data;
    }

    protected function guardarHistorialUpdate(array $data)
    {
        if (!$data['result']) return $data;
        
        $db = \Config\Database::connect();
        // $data['id'] puede venir como array si fue una actualización masiva
        $ids = (array) $data['id'];

        foreach ($ids as $id) {
            $registro = $this->find($id);
            if ($registro) {
                $registro['hist_accion']    = 'UPDATE';
                $registro['hist_hecho_por'] = (function_exists('auth') && auth()->loggedIn()) ? auth()->id() : null;
                $registro['hist_creado_en'] = date('Y-m-d H:i:s');
                
                $db->table('casis_persona_historial')->insert($registro);
            }
        }
        return $data;
    }

    protected function guardarHistorialDelete(array $data)
    {
        $db = \Config\Database::connect();
        $ids = (array) $data['id'];

        foreach ($ids as $id) {
            $registro = $this->find($id);
            if ($registro) {
                $registro['hist_accion']    = 'DELETE';
                $registro['hist_hecho_por'] = (function_exists('auth') && auth()->loggedIn()) ? auth()->id() : null;
                $registro['hist_creado_en'] = date('Y-m-d H:i:s');
                
                $db->table('casis_persona_historial')->insert($registro);
            }
        }
        return $data;
    }
}
