<?php

namespace Modules\Seleccion\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class PostulanteExpedienteController extends BaseController
{
    // Vista principal que carga el layout
    public function index($postulacionId = null, $seccion = 'formacion')
    {
        $seccionesValidas = ['formacion', 'profesion', 'capacitacion', 'experiencia', 'anexos', 'declaraciones'];

        if (!in_array($seccion, $seccionesValidas)) {
            $seccion = 'formacion';
        }

        $data = [
            'postulacionId' => $postulacionId,
            'seccionActiva' => $seccion
        ];

        return view('postulante/expediente/postulacion', $data);
    }

    // Endpoint AJAX que devuelve los fragmentos (partials)
    public function partial($postulacionId = null, $seccion = 'formacion')
    {
        $seccionesValidas = ['formacion', 'profesion', 'capacitacion', 'experiencia', 'anexos', 'declaraciones'];

        if (!in_array($seccion, $seccionesValidas)) {
            $seccion = 'formacion';
        }

        // Datos específicos para cada partial si se requieren
        $data = [
            'postulacionId' => $postulacionId,
        ];

        return view("postulante/expediente/partials/_{$seccion}", $data);
    }
}
