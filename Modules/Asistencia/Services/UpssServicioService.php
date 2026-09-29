<?php

namespace Modules\Asistencia\Services;

use Modules\Asistencia\Models\UpssServicioModel;

class UpssServicioService
{
    private UpssServicioModel $model;

    public function __construct()
    {
        $this->model = new UpssServicioModel();
    }

    public function listarUpssServicios(?string $busqueda = null): array
    {
        $builder = $this->model->builder();

        $builder
            ->select('casis_upss_servicio.*, u.ups_nombre, u.ups_codigo')
            ->join(
                'casis_upss u',
                'u.ups_ide = casis_upss_servicio.uss_ups_ide',
                'left'
            )
            ->where('casis_upss_servicio.deleted_at', null);

        if ($busqueda !== null && trim($busqueda) !== '') {
            $term = trim($busqueda);

            $builder->groupStart()
                ->like('casis_upss_servicio.uss_nombre', $term)
                ->orLike('casis_upss_servicio.uss_abreviatura', $term)
                ->orLike('casis_upss_servicio.uss_codigo', $term)
                ->orLike('u.ups_nombre', $term)
                ->groupEnd();
        }

        return $builder
            ->orderBy('casis_upss_servicio.uss_nombre', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function existe(int $id): bool
    {
        return $id > 0 && $this->model->find($id) !== null;
    }

    public function crearUpssServicio(array $datos): bool|array
    {
        $datos = $this->normalizar($datos);
        $errores = $this->validarUpss($datos['uss_ups_ide']);
        if ($errores) {
            return $errores;
        }

        if ($this->model->insert($datos) === false) {
            return $this->model->errors();
        }
        return true;
    }

    public function actualizarUpssServicio(int $id, array $datos): bool|array
    {
        $datos = $this->normalizar($datos);
        $errores = $this->validarUpss($datos['uss_ups_ide']);
        if ($errores) {
            return $errores;
        }

        if ($this->model->update($id, $datos) === false) {
            return $this->model->errors();
        }
        return true;
    }

    public function eliminarUpssServicio(int $id): bool
    {
        try {
            return $this->model->delete($id) !== false;
        } catch (\Throwable $e) {
            log_message('error', 'No se pudo eliminar servicio ' . $id . ': ' . $e->getMessage());
            return false;
        }
    }

    private function normalizar(array $datos): array
    {
        $datos['uss_ups_ide'] = (int) ($datos['uss_ups_ide'] ?? 0);
        $datos['uss_codigo'] = trim((string) ($datos['uss_codigo'] ?? ''));
        $datos['uss_nombre'] = trim((string) ($datos['uss_nombre'] ?? ''));
        $datos['uss_abreviatura'] = strtoupper(trim((string) ($datos['uss_abreviatura'] ?? '')));
        $datos['uss_estado'] = (string) ($datos['uss_estado'] ?? '1');
        return $datos;
    }

    private function validarUpss(int $upssId): array
    {
        if ($upssId <= 0) {
            return ['uss_ups_ide' => 'Seleccione una UPSS.'];
        }

        // Ajuste ups_estado si la columna de estado del catálogo UPSS tiene otro nombre.
        $upss = db_connect()->table('casis_upss')
            ->select('ups_ide')
            ->where('ups_ide', $upssId)
            ->where('ups_estado', 1)
            ->get()->getRowArray();

        return $upss ? [] : ['uss_ups_ide' => 'La UPSS seleccionada no existe o está inactiva.'];
    }
}
