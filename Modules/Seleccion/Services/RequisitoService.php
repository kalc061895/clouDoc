<?php

namespace Modules\Seleccion\Services;

use Modules\Seleccion\Models\ConvocatoriaCargoModel;
use Modules\Seleccion\Models\RequisitoExperienciaModel;
use Modules\Seleccion\Models\RequisitoFormacionModel;
use Modules\Seleccion\Models\RequisitoModel;

class RequisitoService
{
    protected $db;
    protected RequisitoModel $requisitoModel;
    protected RequisitoFormacionModel $formacionModel;
    protected RequisitoExperienciaModel $experienciaModel;
    protected ConvocatoriaCargoModel $convocatoriaCargoModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->requisitoModel = new RequisitoModel();
        $this->formacionModel = new RequisitoFormacionModel();
        $this->experienciaModel = new RequisitoExperienciaModel();
        $this->convocatoriaCargoModel = new ConvocatoriaCargoModel();
    }

    public function getRequisitosByCargo(int $cargoId): array
    {
        $requisitos = $this->requisitoModel
            ->where('req_cco_ide', $cargoId)
            ->orderBy('req_orden', 'ASC')
            ->orderBy('req_ide', 'ASC')
            ->findAll();

        foreach ($requisitos as &$requisito) {
            $requisito['detalle'] = null;
            if ($requisito['req_tipo'] === 'FORMACION') {
                $requisito['detalle'] = $this->formacionModel
                    ->select('selec_requisito_formacion.*, f.nfo_nombre, p.pro_nombre')
                    ->join('selec_niveles_formacion f', 'f.nfo_ide = rfo_nfo_ide', 'left')
                    ->join('selec_profesiones p', 'p.pro_ide = rfo_pro_ide', 'left')
                    ->where('rfo_req_ide', $requisito['req_ide'])
                    ->first();
            } elseif ($requisito['req_tipo'] === 'EXPERIENCIA') {
                $requisito['detalle'] = $this->experienciaModel
                    ->where('rex_req_ide', $requisito['req_ide'])
                    ->first();
            }
        }

        return $requisitos;
    }

    public function guardarRequisito(array $data): bool
    {
        if (empty($data['req_cco_ide']) || !$this->convocatoriaCargoModel->find($data['req_cco_ide'])) {
            return false;
        }

        $this->db->transStart();
        $reqId = $this->requisitoModel->insert($this->datosRequisito($data, (int) $data['req_cco_ide']));
        $this->guardarDetalle((int) $reqId, $data);
        $this->db->transComplete();

        return $this->db->transStatus();
    }

    public function actualizarRequisito(int $reqId, array $data): bool
    {
        $requisito = $this->requisitoModel->find($reqId);
        if (!$requisito) {
            return false;
        }

        $this->db->transStart();
        $this->requisitoModel->update($reqId, $this->datosRequisito($data, (int) $requisito['req_cco_ide']));
        $this->formacionModel->where('rfo_req_ide', $reqId)->delete();
        $this->experienciaModel->where('rex_req_ide', $reqId)->delete();
        $this->guardarDetalle($reqId, $data);
        $this->db->transComplete();

        return $this->db->transStatus();
    }

    public function eliminarRequisito(int $reqId): bool
    {
        return (bool) $this->requisitoModel->delete($reqId);
    }

    private function datosRequisito(array $data, int $cargoId): array
    {
        return [
            'req_cco_ide' => $cargoId,
            'req_codigo' => $data['req_codigo'] ?? null,
            'req_nombre' => trim($data['req_nombre']),
            'req_descripcion' => trim($data['req_descripcion'] ?? '') ?: null,
            'req_tipo' => $data['req_tipo'],
            'req_obligatorio' => isset($data['req_obligatorio']) ? 1 : 0,
            'req_puntaje' => isset($data['req_puntaje']) ? 1 : 0,
            'req_orden' => !empty($data['req_orden']) ? (int) $data['req_orden'] : 1,
        ];
    }

    private function guardarDetalle(int $reqId, array $data): void
    {
        if ($data['req_tipo'] === 'FORMACION') {
            $this->formacionModel->insert([
                'rfo_req_ide' => $reqId,
                'rfo_nfo_ide' => !empty($data['rfo_nfo_ide']) ? $data['rfo_nfo_ide'] : null,
                'rfo_pro_ide' => !empty($data['rfo_pro_ide']) ? $data['rfo_pro_ide'] : null,
                'rfo_grado' => trim($data['rfo_grado'] ?? '') ?: null,
            ]);
        }

        if ($data['req_tipo'] === 'EXPERIENCIA') {
            $this->experienciaModel->insert([
                'rex_req_ide' => $reqId,
                'rex_anios' => (int) ($data['rex_anios'] ?? 0),
                'rex_meses' => (int) ($data['rex_meses'] ?? 0),
                'rex_dias' => (int) ($data['rex_dias'] ?? 0),
                'rex_tipo_experiencia' => trim($data['rex_tipo_experiencia'] ?? '') ?: null,
                'rex_especifica' => isset($data['rex_especifica']) ? 1 : 0,
            ]);
        }
    }
}
