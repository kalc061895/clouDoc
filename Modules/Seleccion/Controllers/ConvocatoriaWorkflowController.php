<?php

namespace Modules\Seleccion\Controllers;

use App\Core\Controllers\BaseModuleController;
use Modules\Seleccion\Services\ConvocatoriaWorkflowService;
use CodeIgniter\Exceptions\PageNotFoundException;

class ConvocatoriaWorkflowController extends BaseModuleController
{
    private ConvocatoriaWorkflowService $service;

    public function __construct()
    {
        $this->service = new ConvocatoriaWorkflowService();
    }

    public function index()
    {
        return view('Modules\Seleccion\Views\convocatorias\index');
    }

    /**
     * Muestra la vista de detalle/configuración de una convocatoria
     */
    public function detalle(int $id, string $seccion = 'resumen')
    {
        $convocatoria = $this->service->obtener($id);

        if (!$convocatoria) {
            throw PageNotFoundException::forPageNotFound('Convocatoria no encontrada.');
        }

        $seccionesValidas = ['resumen', 'cargos', 'requisitos', 'cronograma', 'documentos', 'anexos', 'comision', 'evaluacion', 'resultados', 'impugnaciones'];
        if (!in_array($seccion, $seccionesValidas, true)) {
            $seccion = 'resumen';
        }

        return view('Modules\Seleccion\Views\convocatorias\detalle', [
            'convocatoriaId' => $id,
            'seccionActiva'  => $seccion,
            'convocatoria'   => $convocatoria,
        ]);
    }

    /**
     * API JSON para obtener datos de la convocatoria
     */
    public function apiDetalle(int $id)
    {
        $convocatoria = $this->service->obtener($id);
        if (!$convocatoria) {
            return $this->jsonResponse('error', 'Convocatoria no encontrada.', [], 404);
        }
        return $this->jsonResponse('success', 'Convocatoria cargada.', $convocatoria);
    }

    /**
     * POST: Publicar convocatoria
     */
    public function publicar(int $id)
    {
        $resultado = $this->service->publicar($id);
        return $this->response->setStatusCode($resultado['code'])->setJSON($resultado);
    }

    /**
     * Retorna el HTML de un partial de pestaña vía AJAX
     */
    public function obtenerPartial(int $id, string $seccion)
    {
        $vistasPermitidas = ['resumen', 'cargos', 'requisitos', 'cronograma', 'documentos', 'anexos', 'comision', 'evaluacion', 'resultados', 'impugnaciones'];

        if (!in_array($seccion, $vistasPermitidas)) {
            return $this->response->setStatusCode(404)->setBody('Sección no válida');
        }

        $data = [
            'convocatoriaId' => $id,
            'seccionActiva'  => $seccion,
        ];

        return view("Modules\Seleccion\Views\convocatorias\partials\\{$seccion}", $data);
    }
}
