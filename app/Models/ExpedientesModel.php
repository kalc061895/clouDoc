<?php

namespace App\Models;

use CodeIgniter\Model;

class ExpedientesModel extends Model
{
    protected $table            = 'expedientes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'numero_expediente', 'procedencia', 'fecha_recepcion',
        'folios', 'tipo_expediente_id', 'tipo_documento', 'numero_documento',
        'entidad_id', 'asunto', 'descripcion', 'atencion_oficina_id',
        'observacion', 'activo'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getNuevosExpedientesExternos()
    {
        $db = \Config\Database::connect();

        $builder = $db->table('expedientes');
        $builder->select('expedientes.*,entidad.*');
        $builder->where('movimientos.id', null);
        $builder->join(
            'movimientos',
            'expedientes.id = movimientos.expediente_id',
            'left'
        );
        $builder->orderBy('expedientes.numero_expediente DESC');
        $builder->join(
            'entidad',
            'expedientes.entidad_id = entidad.id',
            'inner'
        );

        $query = $builder->get();

        return $query->getResultObject();
    }
    public function detalleExpediente($id) {
        $db = \Config\Database::connect();

        $builder = $db->table('expedientes');

        $builder->select(
            'expedientes.id as id,numero_expediente,expedientes.fecha_recepcion as recibido,folios,asunto,
            entidad.nombre as remitente,num_documento,telefono,correo_electronico,
            local_path,drive_path,
            tipo_expediente.nombre as tipoexpediente'
        );
        $builder->where('expedientes.id',
            $id
        );
        $builder->join(
            'movimientos',
            'expedientes.id = movimientos.expediente_id',
            'left'
        );
        $builder->join(
            'entidad',
            'expedientes.entidad_id = entidad.id',
            'inner'
        );
        $builder->join(
            'adjuntos',
            'expedientes.id = adjuntos.expediente_id',
            'inner'
        );
        $builder->join(
            'tipo_expediente',
            'tipo_expediente.id = expedientes.tipo_expediente_id',
            'inner'
        );

        $builder->orderBy('expedientes.numero_expediente DESC');
        $builder->groupBy('numero_expediente');

        $query = $builder->get();

        return $query->getResultObject();
    }
    public function getBuscarExpediente($numero_expediente,$anio) {
        $db = \Config\Database::connect();

        $builder = $db->table('expedientes');

        $builder->select(
            'expedientes.id as id,numero_expediente,expedientes.fecha_recepcion as recibido,folios,asunto,
            entidad.nombre as remitente, entidad.id as remitente_id,num_documento,telefono,correo_electronico, numero_documento,
            local_path,drive_path,
            tipo_expediente.nombre as tipoexpediente,
            tipo_expediente.id as tipoexpediente_id'
        );

        //$builder->where('YEAR(expedientes.fecha_recepcion)',$anio);
        $builder->join(
            'movimientos',
            'expedientes.id = movimientos.expediente_id',
            'left'
        );
        $builder->join(
            'entidad',
            'expedientes.entidad_id = entidad.id',
            'inner'
        );
        $builder->join(
            'adjuntos',
            'expedientes.id = adjuntos.expediente_id',
            'inner'
        );
        $builder->join(
            'tipo_expediente',
            'tipo_expediente.id = expedientes.tipo_expediente_id',
            'inner'
        );
        $builder->where('numero_expediente',$numero_expediente);
        
        $query = $builder->get();
        return $query->getResultObject();
    }

    public function getAnios() {
        $db = \Config\Database::connect();

        $builder = $db->table('anios');
        $builder->orderBy('nombre DESC');

        $query = $builder->get();

        return $query->getResultArray();
    }
}
