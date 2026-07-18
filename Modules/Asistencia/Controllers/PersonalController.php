<?php

namespace Modules\Asistencia\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Asistencia\Services\PersonalService;

class PersonalController extends BaseController
{
    use ResponseTrait;
    protected $personalService;

    public function __construct()
    {
        $this->personalService = new PersonalService();
    }

    public function nuevo()
    {
        // Jalamos catálogos necesarios para los selectores de los Tabs
        $data = $this->personalService->obtenerCatalogosFormulario();
        return view('Modules\Asistencia\Views\personal\nuevo_personal_view', $data);
    }

    /**
     * API: Verifica si la persona ya existe en el sistema general base
     * POST /personal/api/verificar-identidad
     */
    public function apiVerificarIdentidad()
    {
        $numDocumento = $this->request->getVar('per_numero_documento');
        $tipoDocumento = $this->request->getVar('per_tipo_documento') ?? 1;

        if (empty($numDocumento)) {
            return $this->fail('El número de documento es obligatorio.', 400);
        }

        $resultado = $this->personalService->verificarOcrearPersonaBase($numDocumento, $tipoDocumento);

        return $this->respond([
            'status' => 'success',
            'data'   => $resultado // Retorna la info de la persona + flag si ya es Personal activo
        ]);
    }

    /**
     * API: Crear Personal Integral (Transaccional)
     * POST /personal/api
     */
    public function apiCrear()
    {
        $payload = $this->request->getPost();

        // El servicio maneja internamente la transacción SQL para asegurar consistencia
        $resultado = $this->personalService->registrarNuevoPersonal($payload);

        if (is_array($resultado)) {
            return $this->fail($resultado, 400); // Errores de validación
        }

        return $this->respondCreated([
            'status'  => 'success',
            'message' => 'Colaborador registrado e incorporado correctamente en su respectiva oficina.'
        ]);
    }
    
}
