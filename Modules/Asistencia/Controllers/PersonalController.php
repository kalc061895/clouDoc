<?php

namespace Modules\Asistencia\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use Modules\Asistencia\Services\PersonalService;

class PersonalController extends BaseController
{
    use ResponseTrait;

    protected $personalService;

    public function __construct()
    {
        // Instanciamos el servicio del módulo
        $this->personalService = new PersonalService();
    }

    public function index()
    {
        return view('Modules\Asistencia\Views\personal\listar_personal_view.php');
    }

    public function apiListar()
    {
        $data = $this->personalService->getListadoPersonal();
        return $this->respond(['status' => 'success', 'data' => $data], 200);
    }

    public function apiDarDeBaja($id)
    {
        $exito = $this->personalService->darDeBajaPersonal((int) $id);

        if ($exito) {
            return $this->respond(['status' => 'success', 'message' => 'Personal dado de baja correctamente.'], 200);
        }

        return $this->respond(['status' => 'error', 'message' => 'No se pudo procesar la baja.'], 400);
    }
    // --- VISTAS ---
    public function nuevo()
    {
        return view('Modules\Asistencia\Views\personal\registrar_wizard');
    }

    public function editar($id)
    {
        $personal = $this->personalService->getPersonalParaEditar((int) $id);

        if (!$personal) {
            return redirect()->to(base_url('personal'))->with('error', 'El personal solicitado no existe.');
        }

        // Pasamos el registro a la vista
        return view('Modules\Asistencia\Views\personal\editar_wizard', ['personal' => $personal]);
    }

    /**
     * Procesa la actualización desde el Wizard mediante AJAX
     */
    public function apiActualizarPersonal($id)
    {
        // 1. Reglas de Validación rigurosas (Similares al guardado)
        $rules = [
            'per_tdi_ide' => 'required',
            'per_numero_documento' => 'required|min_length[8]',
            'per_nombre' => 'required',
            'per_paterno' => 'required',
            'per_materno' => 'required',
            'perl_mco_ide' => 'required',
            'perl_car_ide' => 'required',
            'perl_est_ide' => 'required',
            'perl_fecha_ingreso' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Por favor verifique los campos obligatorios del formulario.',
                'errors' => $this->validator->getErrors()
            ], 400);
        }

        // 2. Extraer los datos enviados por el formulario POST
        $datosFormulario = $this->request->getPost();

        // 3. Validación de Negocio Extra: Evitar duplicar DNI de otra persona existente
        $db = \Config\Database::connect();
        $documentoExiste = $db->table('casis_persona as p')
            ->join('casis_personal as pl', 'pl.perl_per_ide = p.per_ide')
            ->where('p.per_numero_documento', $datosFormulario['per_numero_documento'])
            ->where('pl.perl_ide !=', (int) $id) // Excluimos al registro actual que estamos editando
            ->countAllResults();

        if ($documentoExiste > 0) {
            return $this->respond([
                'status' => 'error',
                'message' => 'El número de documento ingresado ya está registrado en otro miembro del personal.'
            ], 400);
        }

        // 4. Ejecutar la operación a través del servicio
        $resultado = $this->personalService->actualizarPersonalConHistorial((int) $id, $datosFormulario);

        if ($resultado) {
            return $this->respond([
                'status' => 'success',
                'message' => 'Expediente del trabajador actualizado e historial respaldado con éxito.'
            ], 200);
        }

        return $this->respond([
            'status' => 'error',
            'message' => 'Ocurrió un error en el servidor al intentar actualizar el registro.'
        ], 500);
    }

    // --- SELECTORES DINÁMICOS DEL WIZARD (DELEGADOS AL SERVICIO) ---

    public function apiSelectTiposDocumento()
    {
        $data = $this->personalService->getSelectTiposDocumento();
        return $this->respond(['status' => 'success', 'data' => $data], 200);
    }

    public function apiSelectEstablecimientos()
    {
        $data = $this->personalService->getSelectEstablecimientos();
        return $this->respond(['status' => 'success', 'data' => $data], 200);
    }

    public function apiSelectModalidadesContrato()
    {
        $data = $this->personalService->getSelectModalidadesContrato();
        return $this->respond(['status' => 'success', 'data' => $data], 200);
    }

    public function apiSelectCargos()
    {
        $data = $this->personalService->getSelectCargos();
        return $this->respond(['status' => 'success', 'data' => $data], 200);
    }

    public function apiSelectProfesiones()
    {
        $data = $this->personalService->getSelectProfesiones();
        return $this->respond(['status' => 'success', 'data' => $data], 200);
    }



    public function apiSelectUnidades()
    {
        $estIde = $this->request->getGet('est_ide');
        if (!$estIde)
            return $this->respond(['status' => 'success', 'data' => []], 200);

        $data = $this->personalService->getSelectUnidades((int) $estIde);
        return $this->respond(['status' => 'success', 'data' => $data], 200);
    }

    public function apiSelectOficinas()
    {
        $estIde = $this->request->getGet('est_ide');
        if (!$estIde)
            return $this->respond(['status' => 'success', 'data' => []], 200);

        $data = $this->personalService->getSelectOficinas((int) $estIde);
        return $this->respond(['status' => 'success', 'data' => $data], 200);
    }

    public function apiGuardarWizard()
    {
        // Capturamos el Payload JSON del request asíncrono
        $json = $this->request->getJSON(true);

        if (empty($json)) {
            return $this->respond(['status' => 'error', 'messages' => ['payload' => 'No se recibieron datos válidos.']], 400);
        }

        // Opcional: Aquí puedes meter las reglas del Validation Service de CodeIgniter 4
        // antes de pasárselo al servicio.

        // Delegamos de golpe al servicio
        $resultado = $this->personalService->guardarPersonalWizard($json);

        if ($resultado['status'] === 'success') {
            return $this->respond($resultado, 200);
        }

        // Si falló por validación de base de datos interna, mandamos un código 400
        return $this->respond(['status' => 'error', 'messages' => ['database' => $resultado['message']]], 400);
    }

    public function apiSelectColegios()
    {
        $proIde = $this->request->getGet('pro_ide');
        $data = $this->personalService->getSelectColegios($proIde ? (int) $proIde : null);
        return $this->respond(['status' => 'success', 'data' => $data], 200);
    }

    public function apiSelectEspecialidades()
    {
        $proIde = $this->request->getGet('pro_ide');
        $data = $this->personalService->getSelectEspecialidades($proIde ? (int) $proIde : null);
        return $this->respond(['status' => 'success', 'data' => $data], 200);
    }

}