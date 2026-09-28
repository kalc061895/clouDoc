<?php

namespace Modules\Asistencia\Services;

use Modules\Asistencia\Models\ServicioModel;

class ServicioService
{
    protected $servicioModel;

    public function __construct()
    {
        $this->servicioModel = new ServicioModel();
    }

    /**
     * Listar servicios médicos/administrativos asociando su UPSS contenedora
     */
    public function listarServicios(?string $busqueda = null): array
    {
        $builder = $this->servicioModel->builder();
        $builder->select('casis_servicio.*, u.ups_nombre, u.ups_codigo')
            ->join('casis_upss u', 'u.ups_ide = casis_servicio.ser_upss', 'left');

        if (!empty($busqueda)) {
            $builder->groupStart()
                ->like('casis_servicio.ser_nombre', $busqueda)
                ->orLike('casis_servicio.ser_abreviatura', $busqueda)
                ->orLike('casis_servicio.ser_departamento', $busqueda)
                ->orLike('u.ups_nombre', $busqueda)
                ->groupEnd();
        }

        return $builder->orderBy('casis_servicio.ser_departamento', 'ASC')
            ->orderBy('casis_servicio.ser_nombre', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Registrar un nuevo servicio
     */
    public function crearServicio(array $datos): bool|array
    {
        $datos['ser_upss'] = !empty($datos['ser_upss']) ? (int)$datos['ser_upss'] : null;

        if ($this->servicioModel->insert($datos) === false) {
            return $this->servicioModel->errors();
        }

        return true;
    }

    /**
     * Modificar un servicio existente
     */
    public function actualizarServicio(int $id, array $datos): bool|array
    {
        $datos['ser_upss'] = !empty($datos['ser_upss']) ? (int)$datos['ser_upss'] : null;

        if ($this->servicioModel->update($id, $datos) === false) {
            return $this->servicioModel->errors();
        }

        return true;
    }

    /**
     * Eliminación física (Dado que $useSoftDeletes = false en el modelo)
     */
    public function eliminarServicio(int $id): bool
    {
        return $this->servicioModel->delete($id) ? true : false;
    }
}
