<?php

namespace Modules\Asistencia\Services;

use Modules\Asistencia\Models\DiaFeriadoModel;

class DiaFeriadoService
{
    protected $diaFeriadoModel;

    public function __construct()
    {
        $this->diaFeriadoModel = new DiaFeriadoModel();
    }

    /**
     * Listar feriados con filtros de búsqueda u ordenados cronológicamente
     */
    public function listarFeriados(?string $busqueda = null, ?int $anio = null): array
    {
        $builder = $this->diaFeriadoModel->builder();
        $builder->where('deleted_at', null);

        if (!empty($anio)) {
            $builder->where('df_fecha >=', "$anio-01-01")
                ->where('df_fecha <=', "$anio-12-31");
        }

        if (!empty($busqueda)) {
            $builder->like('df_nombre', $busqueda);
        }

        return $builder->orderBy('df_fecha', 'ASC')->get()->getResultArray();
    }

    /**
     * Crear un nuevo feriado controlando que no se duplique la fecha
     */
    public function crearFeriado(array $datos): bool|array
    {
        $datos['created_by'] = session()->get('user_id') ?? 1;
        $datos['df_repetitivo'] = $datos['df_repetitivo'] ?? 0;

        // Validar duplicidad de fecha (para registros activos)
        $existe = $this->diaFeriadoModel->where('df_fecha', $datos['df_fecha'])->first();
        if ($existe) {
            return ['df_fecha' => 'Ya existe un día feriado registrado para esta fecha.'];
        }

        if ($this->diaFeriadoModel->insert($datos) === false) {
            return $this->diaFeriadoModel->errors();
        }

        return true;
    }

    /**
     * Actualizar datos del feriado
     */
    public function actualizarFeriado(int $id, array $datos): bool|array
    {
        $datos['updated_by'] = session()->get('user_id') ?? 1;
        $datos['df_repetitivo'] = $datos['df_repetitivo'] ?? 0;

        // Validar duplicidad de fecha excluyendo el registro actual
        $existe = $this->diaFeriadoModel->where('df_fecha', $datos['df_fecha'])
            ->where('df_ide !=', $id)
            ->first();
        if ($existe) {
            return ['df_fecha' => 'Ya existe otro día feriado registrado para esta fecha.'];
        }

        if ($this->diaFeriadoModel->update($id, $datos) === false) {
            return $this->diaFeriadoModel->errors();
        }

        return true;
    }

    /**
     * Eliminar feriado (Soft Delete)
     */
    public function eliminarFeriado(int $id): bool
    {
        return $this->diaFeriadoModel->delete($id) ? true : false;
    }
}
