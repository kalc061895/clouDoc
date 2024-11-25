<?php

namespace App\Models;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Model;
use SebastianBergmann\Type\FalseType;

class ExpedientesModel extends Model
{
    protected $table            = 'expedientes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'numero_expediente',
        'procedencia',
        'fecha_recepcion',
        'folios',
        'tipo_expediente_id',
        'tipo_documento',
        'numero_documento',
        'entidad_id',
        'asunto',
        'descripcion',
        'atencion_oficina_id',
        'observacion',
        'activo'
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
        $builder->select('expedientes.*,entidad.nombre,entidad.tipo,entidad.num_documento,entidad.correo_electronico,movimientos.estado');
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
    public function detalleExpediente($id)
    {
        $db = \Config\Database::connect();

        $builder = $db->table('expedientes');

        $builder->select(
            'expedientes.id as id,numero_expediente,expedientes.fecha_recepcion as recibido,folios,asunto,
            entidad.nombre as remitente,num_documento,telefono,correo_electronico,
            local_path,drive_path,
            tipo_expediente.nombre as tipoexpediente'
        );
        $builder->where(
            'expedientes.id',
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
    public function getBuscarExpediente($numero_expediente, $anio)
    {
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
        $builder->where('numero_expediente', $numero_expediente);

        $query = $builder->get();
        return $query->getResultObject();
    }
    public function getMovimientos($id_expediente)
    {
        $db = \Config\Database::connect();

        $builder = $db->table('movimientos');

        $builder->select(
            '
            movimientos.id,
            movimientos.numero_movimiento,
            oficina_procedencia.id as oficina_procedencia_id,
            oficina_procedencia.nombre as oficina_procedencia,
            oficina_procedencia.abreviatura as oficina_procedencia_abreviatura,
            oficina_destino.id as oficina_destino_id,
            oficina_destino.nombre as oficina_destino,
            oficina_destino.abreviatura as oficina_destino_abreviatura,
            movimientos.accion,
            movimientos.observacion,
            movimientos.prioridad,
            movimientos.estado,
            movimientos.fecha_envio,
            movimientos.fecha_recepcion,
            movimientos.fecha_culminacion'
        );

        $builder->join(
            'oficinas as oficina_procedencia',
            'oficina_procedencia.id = movimientos.oficina_procedencia_id',
            'JOIN'
        );

        $builder->join(
            'oficinas as oficina_destino',
            'oficina_destino.id = movimientos.oficina_destino_id',
            'JOIN'
        );

        $builder->orderBy('movimientos.id DESC');
        $builder->where('movimientos.expediente_id', $id_expediente);

        $query = $builder->get();
        return $query->getResultObject();
    }
    public function getAnios()
    {
        $db = \Config\Database::connect();

        $builder = $db->table('anios');
        $builder->orderBy('nombre DESC');

        $query = $builder->get();

        return $query->getResultArray();
    }
    public function getExpedientesOficina($id_oficina, $where = false)
    {
        $db = \Config\Database::connect();

        $builder = $db->table('expedientes');
        $builder->select('expedientes.*,entidad.nombre,entidad.tipo,entidad.num_documento,entidad.correo_electronico,movimientos.estado');
        $builder->where('movimientos.oficina_destino_id', $id_oficina);

        if ($where != false) {
            $builder->where('movimientos.estado', $where);
        }

        $builder->join(
            'movimientos',
            'expedientes.id = movimientos.expediente_id',
            'left'
        );

        $builder->orderBy('movimientos.id', 'DESC');
        $builder->groupBy('movimientos.expediente_id');
        $builder->orderBy('expedientes.numero_expediente', 'DESC');
        $builder->join(
            'entidad',
            'expedientes.entidad_id = entidad.id',
            'inner'
        );

        $query = $builder->get();

        return $query->getResultObject();
    }
    public function getEmitidos($id_oficina)
    {
        $db = \Config\Database::connect();

        $builder = $db->table('expedientes');
        $builder->select('expedientes.*,entidad.nombre,entidad.tipo,entidad.num_documento,entidad.correo_electronico,movimientos.estado,tipo_expediente.nombre as nombre_documento');
        //$builder->where('.oficina_destino_id', $id_oficina);
        $builder->where('expedientes.atencion_oficina_id', $id_oficina);

        $builder->join(
            'movimientos',
            'expedientes.id = movimientos.expediente_id',
            'left'
        );
        $builder->join(
            'tipo_expediente',
            'expedientes.tipo_expediente_id = tipo_expediente.id',
            'left'
        );

        $builder->orderBy('movimientos.id', 'DESC');
        $builder->groupBy('movimientos.expediente_id');
        $builder->orderBy('expedientes.numero_expediente', 'DESC');
        $builder->join(
            'entidad',
            'expedientes.entidad_id = entidad.id',
            'inner'
        );

        $query = $builder->get();

        return $query->getResultObject();
    }

    public function getExpedientesOficinaEstado($id_oficina, $where = false)
    {
        $db = \Config\Database::connect();

        $builder = $db->table('expedientes');
        $builder->select('expedientes.*, entidad.nombre,entidad.tipo,entidad.num_documento,entidad.correo_electronico, estado');

        // Unir la subconsulta con la tabla de movimientos para obtener el último movimiento
        $builder->join(
            '(SELECT expediente_id, estado, oficina_procedencia_id FROM movimientos
        WHERE id IN (SELECT MAX(id) FROM movimientos GROUP BY expediente_id)) AS latest_movimiento',
            'expedientes.id = latest_movimiento.expediente_id',
            'left'
        );
        // Unir con la tabla de entidad
        $builder->join(
            'entidad',
            'expedientes.entidad_id = entidad.id',
            'left'
        );

        // Filtrar por oficina_procedencia_id y estado si es necesario
        $builder->where('oficina_procedencia_id', $id_oficina);

        if ($where !== false) {
            $builder->where('estado', $where);
        }

        // Ordenar los resultados
        $builder->orderBy('expedientes.numero_expediente', 'DESC');

        $query = $builder->get();

        return $query->getResultObject();
    }
    public function getExpedientesOficinaTodo($id_oficina)
    {
        $db = \Config\Database::connect();

        $builder = $db->table('expedientes');
        $builder->select('expedientes.*, entidad.nombre,entidad.tipo,entidad.num_documento,entidad.correo_electronico, estado');

        // Unir la subconsulta con la tabla de movimientos para obtener el último movimiento
        $builder->join(
            '(SELECT expediente_id, estado, oficina_procedencia_id, oficina_destino_id FROM movimientos
        WHERE id IN (SELECT MAX(id) FROM movimientos WHERE (oficina_destino_id = "' . $id_oficina . '") GROUP BY expediente_id)) AS latest_movimiento',
            'expedientes.id = latest_movimiento.expediente_id',
            'INNER'
        );
        // Unir con la tabla de entidad
        $builder->join(
            'entidad',
            'expedientes.entidad_id = entidad.id',
            'left'
        );

        // Ordenar los resultados
        $builder->orderBy('expedientes.numero_expediente', 'DESC');

        $query = $builder->get();

        return $query->getResultObject();
    }

    public function getExpedientesTodo()
    {
        $db = \Config\Database::connect();

        $builder = $db->table('expedientes');

        $builder->select('expedientes.*,entidad.nombre,entidad.tipo,entidad.num_documento,entidad.correo_electronico,estado');

        $builder->join(
            '(SELECT expediente_id, estado FROM movimientos
        WHERE id IN (SELECT MAX(id) FROM movimientos GROUP BY expediente_id)) AS latest_movimiento',
            'expedientes.id = latest_movimiento.expediente_id',
            'left'
        );

        $builder->groupBy('expedientes.id');

        $builder->orderBy('expedientes.numero_expediente', 'DESC');

        $builder->join(
            'entidad',
            'expedientes.entidad_id = entidad.id',
            'inner'
        );
        $builder->limit(25);

        $query = $builder->get();

        return $query->getResultObject();
    }

    public function getFilteredData($start, $length, $searchValue, $order)
    {
        $builder = $this->builder();
        $builder->select('numero_expediente, nombre, asunto, estado, expedientes.fecha_recepcion, expedientes.id, correo_electronico');
        $builder->join(
            'entidad',
            'expedientes.entidad_id = entidad.id',
            'left'
        );


        // Unir con la tabla movimientos para obtener los detalles del último movimiento
        $builder->join(
            '(SELECT expediente_id, estado FROM movimientos
        WHERE id IN (SELECT MAX(id) FROM movimientos GROUP BY expediente_id)) AS latest_movimiento',
            'expedientes.id = latest_movimiento.expediente_id',
            'left'
        );
        if ($searchValue) {
            $builder->like('numero_expediente', $searchValue);
            $builder->orLike('nombre', $searchValue);
            $builder->orLike('asunto', $searchValue);
            $builder->orLike('expedientes.fecha_recepcion', $searchValue);
        }

        $builder->groupBy('expedientes.id');

        $builder->orderBy('expedientes.numero_expediente', 'DESC');

        // Mapea el índice de columna a nombres de columna
        $columns = ['numero_expediente', 'nombre', 'asunto', 'estado', 'fecha_recepcion', 'numero_expediente'];

        foreach ($order as $value) {
            $columnIndex = $value['column']; // Índice de columna
            $columnName = $columns[$columnIndex]; // Nombre de columna
            $direction = $value['dir']; // 'asc' o 'desc'
            $builder->orderBy($columnName, $direction);
        }

        $builder->limit($length, $start);

        $query = $builder->get();
        $data = $query->getResultArray();

        // Add custom options button for each row
        foreach ($data as &$item) {
            $item['opciones'] = '<button type="button" class="btn btn-sm btn-rounded btn-info" title="Revisar" onclick="RevisarExpediente(' . $item['id'] . ')"><i class="ti ti-eye fs-5"></i> Revisar</button>';
        }
        return $data;
    }

    public function getTotalRecords()
    {
        $builder = $this->builder();
        $builder->select('numero_expediente, nombre, asunto, estado, expedientes.fecha_recepcion, expedientes.id, correo_electronico');

        $builder->join(
            'entidad',
            'expedientes.entidad_id = entidad.id',
            'left'
        );
        $builder->join(
            '(SELECT expediente_id, estado FROM movimientos
        WHERE id IN (SELECT MAX(id) FROM movimientos GROUP BY expediente_id)) AS latest_movimiento',
            'expedientes.id = latest_movimiento.expediente_id',
            'left'
        );

        $builder->groupBy('expedientes.id');

        return $builder->countAllResults();
    }

    public function getTotalFilteredRecords($searchValue)
    {
        $builder = $this->builder();
        $builder->select('numero_expediente, nombre, asunto, estado, expedientes.fecha_recepcion, expedientes.id, correo_electronico');

        $builder->join(
            'entidad',
            'expedientes.entidad_id = entidad.id',
            'LEFT'
        );
        $builder->join(
            '(SELECT expediente_id, estado FROM movimientos
        WHERE id IN (SELECT MAX(id) FROM movimientos GROUP BY expediente_id)) AS latest_movimiento',
            'expedientes.id = latest_movimiento.expediente_id',
            'left'
        );

        $builder->groupBy('expedientes.id');

        if ($searchValue) {
            $builder->like('numero_expediente', $searchValue);
            $builder->orLike('nombre', $searchValue);
            $builder->orLike('asunto', $searchValue);
            $builder->orLike('expedientes.fecha_recepcion', $searchValue);
        }

        return $builder->countAllResults();
    }
    public function getReporteExpedientesFiltrado($where = array())
    {
        $db = \Config\Database::connect();

        $builder = $db->table('expedientes');

        $builder->select('expedientes.*,entidad.nombre,entidad.tipo,entidad.num_documento,entidad.correo_electronico,estado,oficinas.nombre as nombre_oficina,CONCAT(tipo_expediente.nombre," ",numero_documento) as numero_documento');

        $builder->join(
            '(SELECT expediente_id, estado, oficina_destino_id FROM movimientos
        WHERE id IN (SELECT MIN(id) FROM movimientos GROUP BY expediente_id)) AS latest_movimiento',
            'expedientes.id = latest_movimiento.expediente_id',
            'left'
        );
        if (count($where) == 0) {
            $builder->where(new RawSql('DATE(expedientes.fecha_recepcion)'), new RawSql("CURDATE()"));
        } else {
            foreach ($where as $item) {
                $builder->where($item['key'], $item['value']);
            }
        }
        $builder->groupBy('expedientes.id');

        $builder->orderBy('expedientes.numero_expediente', 'DESC');

        $builder->join(
            'oficinas',
            'oficinas.id = oficina_destino_id',
             'inner'
        );
        $builder->join(
            'tipo_expediente',
            'tipo_expediente.id = tipo_expediente_id',
            'inner'
        );
        $builder->join(
            'entidad',
            'expedientes.entidad_id = entidad.id',
            'inner'
        );
        //$builder->limit(25);

        $query = $builder->get();

        return $query->getResultObject();
    }
    public function getExpedienteSelect($where = false, $select = false)
    {
        $db = \Config\Database::connect();

        $builder = $db->table('expedientes');
        if($select){
            $select = "expedientes.id, CONCAT(expedientes.numero_expediente,': ',tipo_expediente.nombre,' ',expedientes.numero_documento,' - ',expedientes.asunto) as text";
        }
        else
        {
            $select = "CONCAT(expedientes.numero_expediente,': ',tipo_expediente.nombre,' ',expedientes.numero_documento,' - ',expedientes.asunto) as id, CONCAT(expedientes.numero_expediente,': ',tipo_expediente.nombre,' ',expedientes.numero_documento,' - ',expedientes.asunto) as text";
        }
        $builder->select($select);
        //$builder->where('.oficina_destino_id', $id_oficina);

        if ($where != false) {
            $builder->like(
                'numero_expediente',
                $where
            );
        }
        $builder->join(
            'movimientos',
            'expedientes.id = movimientos.expediente_id',
            'left'
        );
        $builder->join(
            'tipo_expediente',
            'expedientes.tipo_expediente_id = tipo_expediente.id',
            'left'
        );

        $builder->orderBy('movimientos.id', 'DESC');
        $builder->groupBy('movimientos.expediente_id');
        $builder->orderBy('expedientes.numero_expediente', 'DESC');
        $builder->join(
            'entidad',
            'expedientes.entidad_id = entidad.id',
            'inner'
        );
        $builder->limit(
            10
        );

        $query = $builder->get();

        return $query->getResultObject();
    }
}
