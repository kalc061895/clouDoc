<?php

namespace Modules\Asistencia\Services;

use Modules\Asistencia\Models\OficinaModel;

class OficinaService
{
    protected $oficinaModel;

    public function __construct()
    {
        $this->oficinaModel = new OficinaModel();
    }

    /**
     * Listar oficinas con toda su información relacionada y jerárquica
     */
    public function listarOficinas(?string $busqueda = null): array
    {
        $builder = $this->oficinaModel->builder();
        $usuarios = config('Auth')->tables['users'];

        $builder->select('
                    casis_oficina.*,
                    responsable.username as responsable_username,
                    responsable.nombres as responsable_nombres,
                    responsable.paterno as responsable_paterno,
                    responsable.materno as responsable_materno,
                    padre.ofi_nombre as padre_nombre,
                    casis_tipo_oficina.tofi_nombre as tipo_oficina_nombre,
                    casis_establecimiento.est_nombre as establecimiento_nombre,
                    casis_establecimiento.est_mic_ide as microred_ide,
                    casis_microred.mic_red_ide as red_ide,
                    casis_red.red_dir_ide as diresa_ide
                ')
            ->join('casis_oficina as padre', 'padre.ofi_ide = casis_oficina.ofi_padre_ide', 'left')
            ->join($usuarios . ' as responsable', 'responsable.id = casis_oficina.ofi_responsable_id AND responsable.deleted_at IS NULL', 'left')
            ->join('casis_tipo_oficina', 'casis_tipo_oficina.tofi_ide = casis_oficina.ofi_tofi_ide', 'left')
            ->join('casis_establecimiento', 'casis_establecimiento.est_ide = casis_oficina.ofi_est_ide', 'left')
            ->join('casis_microred', 'casis_microred.mic_ide = casis_establecimiento.est_mic_ide', 'left')
            ->join('casis_red', 'casis_red.red_ide = casis_microred.mic_red_ide', 'left')
            ->where('casis_oficina.deleted_at', null);

        if (!empty($busqueda)) {
            $builder->like('casis_oficina.ofi_nombre', $busqueda);
        }

        return $builder->orderBy('casis_oficina.ofi_nombre', 'ASC')->get()->getResultArray();
    }

    /**
     * Listar oficinas de un establecimiento específico para usar como "Oficina Padre"
     */
    public function listarPorEstablecimiento(int $estIde): array
    {
        return $this->oficinaModel->where('ofi_est_ide', $estIde)
            ->where('ofi_estado', 1)
            ->orderBy('ofi_nombre', 'ASC')
            ->findAll();
    }

    public function crearOficina(array $datos): bool|array
    {
        if ($error = $this->validarResponsable($datos)) {
            return $error;
        }
        $datos['created_by'] = session()->get('user_id') ?? 1;
        $datos['ofi_estado'] = $datos['ofi_estado'] ?? 1;

        if ($this->oficinaModel->insert($datos) === false) {
            return $this->oficinaModel->errors();
        }

        return true;
    }

    public function actualizarOficina(int $id, array $datos): bool|array
    {
        if ($error = $this->validarResponsable($datos)) {
            return $error;
        }
        $datos['updated_by'] = session()->get('user_id') ?? 1;

        if ($this->oficinaModel->update($id, $datos) === false) {
            return $this->oficinaModel->errors();
        }

        return true;
    }

    public function eliminarOficina(int $id): bool
    {
        return $this->oficinaModel->delete($id) ? true : false;
    }

    public function listarResponsables(): array
    {
        return db_connect()->table(config('Auth')->tables['users'])
            ->select('id, username, nombres, paterno, materno, dni')
            ->where('deleted_at', null)
            ->orderBy('nombres', 'ASC')->orderBy('username', 'ASC')
            ->get()->getResultArray();
    }

    private function validarResponsable(array &$datos): array
    {
        if (!array_key_exists('ofi_responsable_id', $datos)) {
            return [];
        }

        $id = $datos['ofi_responsable_id'];
        if ($id === '' || $id === null) {
            $datos['ofi_responsable_id'] = null;
            return [];
        }

        if ((!is_int($id) && !is_string($id))
            || !ctype_digit((string) $id) || (int) $id <= 0
            || !db_connect()->table(config('Auth')->tables['users'])
                ->where('id', $id)->where('deleted_at', null)->countAllResults()) {
            return ['ofi_responsable_id' => 'Seleccione un usuario existente como responsable o deje Sin responsable.'];
        }

        $datos['ofi_responsable_id'] = (int) $id;
        return [];
    }
}
