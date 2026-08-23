<?php

namespace Modules\Seleccion\Services;

use Modules\Seleccion\Models\ComisionMiembroModel;
use Modules\Seleccion\Models\ComisionModel;
use Modules\Seleccion\Models\ConvocatoriaModel;

class ComisionService
{
    private ComisionModel $comisiones;
    private ComisionMiembroModel $miembros;
    private ConvocatoriaModel $convocatorias;

    public function __construct()
    {
        $this->comisiones = new ComisionModel();
        $this->miembros = new ComisionMiembroModel();
        $this->convocatorias = new ConvocatoriaModel();
    }

    public function listar(int $convocatoriaId): array
    {
        return $this->comisiones->where('com_con_ide', $convocatoriaId)->orderBy('com_fecha_designacion', 'DESC')->findAll();
    }

    public function guardar(array $data): array
    {
        $id = !empty($data['com_ide']) ? (int) $data['com_ide'] : null;
        if (!$this->convocatorias->find((int) $data['com_con_ide']))
            return $this->error(404, 'La convocatoria no existe.');
        if ($id && (!$actual = $this->comisiones->find($id) || (int) $actual['com_con_ide'] !== (int) $data['com_con_ide']))
            return $this->error(404, 'La comision no existe en esta convocatoria.');

        $duplicado = $this->comisiones->where('com_con_ide', $data['com_con_ide'])->where('com_numero', trim($data['com_numero']));
        if ($id)
            $duplicado->where('com_ide !=', $id);
        if ($duplicado->countAllResults())
            return $this->error(409, 'El numero de comision ya esta registrado.');

        $registro = ['com_con_ide' => (int) $data['com_con_ide'], 'com_numero' => trim($data['com_numero']), 'com_fecha_designacion' => $data['com_fecha_designacion'], 'com_documento_ide' => $data['com_documento_ide'] ?: null, 'com_estado' => $data['com_estado']];
        $id = $id ?: $this->comisiones->insert($registro, true);
        if (!empty($data['com_ide']))
            $this->comisiones->update($id, $registro);
        return ['ok' => true, 'code' => 200, 'message' => 'Comision guardada correctamente.', 'data' => $this->comisiones->find($id)];
    }

    public function eliminar(int $id): array
    {
        if (!$this->comisiones->find($id))
            return $this->error(404, 'La comision no existe.');
        if ($this->miembros->where('cmi_com_ide', $id)->countAllResults())
            return $this->error(409, 'No puede eliminar una comision que tiene miembros activos.');
        $this->comisiones->delete($id);
        return ['ok' => true, 'code' => 200, 'message' => 'Comision eliminada correctamente.'];
    }

    public function miembros(int $comisionId): array
    {
        return $this->miembros->select('selec_comision_miembros.*, users.username, users.paterno,users.materno,users.nombres')
            ->join('users', 'users.id = cmi_usu_ide', 'left')->where('cmi_com_ide', $comisionId)->orderBy('cmi_tipo')->findAll();
    }

    public function guardarMiembro(array $data): array
    {
        $data['cmi_documento_ide'] = 1;

        $id = !empty($data['cmi_ide']) ? (int) $data['cmi_ide'] : null;
        if (!$this->comisiones->find((int) $data['cmi_com_ide']))
            return $this->error(404, 'La comision no existe.');
        if ($id && (!$actual = $this->miembros->find($id) || (int) $actual['cmi_com_ide'] !== (int) $data['cmi_com_ide']))
            return $this->error(404, 'El miembro no existe en esta comision.');
        if ($data['cmi_fecha_fin'] && $data['cmi_fecha_inicio'] && strtotime($data['cmi_fecha_fin']) < strtotime($data['cmi_fecha_inicio']))
            return $this->error(422, 'La fecha de fin no puede ser anterior a la fecha de inicio.');

        $duplicado = $this->miembros->where('cmi_com_ide', $data['cmi_com_ide'])->where('cmi_usu_ide', $data['cmi_usu_ide']);
        if ($id)
            $duplicado->where('cmi_ide !=', $id);
        if ($duplicado->countAllResults())
            return $this->error(409, 'El usuario ya es miembro activo de esta comision.');
        if (in_array($data['cmi_tipo'], ['PRESIDENTE', 'SECRETARIO'], true)) {
            $rol = $this->miembros->where('cmi_com_ide', $data['cmi_com_ide'])->where('cmi_tipo', $data['cmi_tipo']);
            if ($id)
                $rol->where('cmi_ide !=', $id);
            if ($rol->countAllResults())
                return $this->error(409, 'Solo puede existir un ' . strtolower($data['cmi_tipo']) . ' activo.');
        }
        $registro = ['cmi_com_ide' => (int) $data['cmi_com_ide'], 'cmi_usu_ide' => (int) $data['cmi_usu_ide'], 'cmi_tipo' => $data['cmi_tipo'], 'cmi_fecha_inicio' => $data['cmi_fecha_inicio'] ?: null, 'cmi_fecha_fin' => $data['cmi_fecha_fin'] ?: null, 'cmi_documento_ide' => $data['cmi_documento_ide'] ?: null];
        $id = $id ?: $this->miembros->insert($registro, true);
        if (!empty($data['cmi_ide']))
            $this->miembros->update($id, $registro);
        return ['ok' => true, 'code' => 200, 'message' => 'Miembro guardado correctamente.', 'data' => $this->miembros->find($id)];
    }

    public function eliminarMiembro(int $id): array
    {
        if (!$this->miembros->find($id))
            return $this->error(404, 'El miembro no existe.');
        $this->miembros->delete($id);
        return ['ok' => true, 'code' => 200, 'message' => 'Miembro retirado correctamente.'];
    }
    private function error(int $code, string $message): array
    {
        return ['ok' => false, 'code' => $code, 'message' => $message];
    }
}
