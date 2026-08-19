<?php

namespace Modules\Seleccion\Controllers;

use App\Controllers\BaseController;
use Modules\Seleccion\Services\ConvocatoriaWorkflowService;
use CodeIgniter\Exceptions\PageNotFoundException;

class ConvocatoriaWorkflowController extends BaseController
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
     * Muestra la configuración de la convocatoria según la pestaña solicitada
     */
    public function detalle(int $id, string $seccion = 'resumen')
    {
        $convocatoria = $this->service->obtener($id);

        if (!$convocatoria) {
            throw PageNotFoundException::forPageNotFound('Convocatoria no encontrada.');
        }

        // Secciones permitidas en la navegación
        $seccionesValidas = ['resumen', 'cargos', 'requisitos', 'cronograma', 'documentos', 'anexos'];
        if (!in_array($seccion, $seccionesValidas, true)) {
            $seccion = 'resumen';
        }

        return view('Modules\Seleccion\Views\convocatorias\detalle', [
            'convocatoriaId' => $id,
            'seccionActiva'  => $seccion,
            'convocatoria'   => $convocatoria
        ]);
    }

    /**
     * Muestra las pestañas de configuración (Resumen, Cargos, Requisitos, etc.)
     */
    public function configuracion(int $id, string $seccion = 'resumen')
    {
        $convocatoria = $this->service->obtener($id);

        if (!$convocatoria) {
            throw PageNotFoundException::forPageNotFound('La convocatoria solicitada no existe.');
        }

        // Obtener la data interna según la pestaña
        $dataSeccion = $this->service->obtenerDetalleSeccion($id, $seccion);

        $data = [
            'convocatoriaId' => $id,
            'seccionActiva'  => $seccion,
            'convocatoria'   => $convocatoria,
            'dataSeccion'    => $dataSeccion
        ];

        // Mapeo flexible de vistas según la sección solicitada
        $vista = "Modules\Seleccion\Views\convocatorias\\{$seccion}";

        return view($vista, $data);
    }

    /**
     * API JSON consumida por $.get('<?= base_url('api/seleccion/convocatorias/' . $convocatoriaId) ?>')
     */
    public function apiDetalle(int $id)
    {
        $convocatoria = $this->service->obtener($id);

        if (!$convocatoria) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Convocatoria no encontrada'
            ])->setStatusCode(404);
        }

        return $this->response->setJSON([
            'status' => true,
            'data'   => $convocatoria
        ]);
    }

    /**
     * API JSON para publicar la convocatoria
     */
    public function publicar(int $id)
    {
        $resultado = $this->service->publicar($id);
        return $this->response->setStatusCode($resultado['code'])->setJSON($resultado);
    }

    // En ConvocatoriasController.php
    public function obtenerPartial($id, $seccion)
    {
        $vistasPermitidas = ['resumen', 'cargos', 'requisitos', 'cronograma', 'documentos', 'anexos'];

        if (!in_array($seccion, $vistasPermitidas)) {
            return $this->response->setStatusCode(404)->setBody('Sección no válida');
        }

        $data = [
            'convocatoriaId' => $id,
            'seccionActiva'  => $seccion
        ];

        // Devuelve SOLO el HTML parcial sin extender ningún layout
        return view("Modules\Seleccion\Views\convocatorias\partials\\{$seccion}", $data);
    }
}
