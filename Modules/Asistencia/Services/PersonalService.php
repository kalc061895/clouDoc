<?php

namespace Modules\Asistencia\Services;

use Modules\Asistencia\Models\PersonaModel;
use Modules\Asistencia\Models\ProfesionModel;
use Modules\Asistencia\Models\PersonalModel;
use Modules\Asistencia\Models\OficinaModel;
use Modules\Asistencia\Models\CargoModel;
use Modules\Asistencia\Models\EspecialidadModel;
use Modules\Asistencia\Models\ProfesionEspecialidadModel;

use Config\Database;
use Exception;

class PersonalService
{
    protected $db;

    // Modelos involucrados en la gestión integral del Personal
    protected $personaModel;
    protected $personalModel;
    protected $oficinaModel;
    protected $cargoModel;
    protected $profesionModel;
    protected $especialidadModel;
    protected $ProfesionEspecialidadModel;

    public function __construct()
    {
        $this->db = Database::connect();

        // Instancia tus modelos (ajusta los namespaces según tu estructura real)
        $this->personaModel       = new PersonaModel();
        $this->personalModel      = new PersonalModel();
        $this->oficinaModel       = new OficinaModel();
        $this->cargoModel         = new CargoModel();
        $this->profesionModel     = new ProfesionModel();
        $this->ProfesionEspecialidadModel = new ProfesionEspecialidadModel();
    }

    /**
     * Obtiene los catálogos requeridos para renderizar los selectores de los Tabs
     */
    public function obtenerCatalogosFormulario(): array
    {
        return [
            'oficinas'       => $this->oficinaModel->orderBy('ofi_nombre', 'ASC')->findAll(),
            'cargos'         => $this->cargoModel->orderBy('car_nombre', 'ASC')->findAll(),
            'profesiones'    => $this->profesionModel->orderBy('pro_nombre', 'ASC')->findAll(),
            'especialidades' => $this->ProfesionEspecialidadModel->findAll()
        ];
    }

    /**
     * Paso 1 (AJAX): Busca una persona por documento. Si no existe, pre-registra una entidad vacía o 
     * retorna la existente para verificar si ya posee un vínculo laboral activo.
     */
    public function verificarOcrearPersonaBase(string $numDocumento, int $tipoDocumento): array
    {
        // 1. Buscar si la persona ya existe en el padrón civil general
        $persona = $this->personaModel->where([
            'per_tdi_ide'   => $tipoDocumento,
            'per_numero_documento' => $numDocumento
        ])->first();

        if (!$persona) {
            // Retornamos estructura limpia lista para el llenado manual si no existe en la base
            return [
                'persona' => [
                    'persona_id'           => null,
                    'per_tdi_ide'   => $tipoDocumento,
                    'per_numero_documento' => $numDocumento,
                    'per_apellido_paterno' => '',
                    'per_apellido_materno' => '',
                    'per_nombres'          => '',
                    'per_correo'           => ''
                ],
                'es_personal_activo' => false
            ];
        }

        // 2. Si la persona existe, validamos si ya cuenta con contrato o plaza activa en la tabla personal
        $personalActivo = $this->personalModel->where([
            'persona_id' => $persona['persona_id'],
            'psn_estado' => 1 // Estado activo en la institución
        ])->first();

        return [
            'persona'            => $persona,
            'es_personal_activo' => !empty($personalActivo)
        ];
    }

    /**
     * Procesa la transacción unificada del Wizard (Tabs 1 al 4)
     * Inserta/Actualiza datos civiles -> Crea registro laboral -> Asigna parámetros biométricos
     */
    public function registrarNuevoPersonal(array $data)
    {
        // Reglas de validación manuales antes de abrir la transacción
        $errores = $this->validarPayloadPersonal($data);
        if (!empty($errores)) {
            return $errores;
        }

        $this->db->transStart();

        try {
            $personaId = $data['persona_id'] ?? null;

            // CASO A: Es una persona completamente nueva (no existía en el paso 1)
            if (empty($personaId)) {
                $datosPersona = [
                    'per_tdi_ide'   => $data['per_tipo_documento'],
                    'per_numero_documento' => $data['per_numero_documento'],
                    'per_apellido_paterno' => trim(mb_strtoupper($data['per_apellido_paterno'])),
                    'per_apellido_materno' => trim(mb_strtoupper($data['per_apellido_materno'])),
                    'per_nombres'          => trim(mb_strtoupper($data['per_nombres'])),
                    'per_correo'           => !empty($data['per_correo']) ? trim($data['per_correo']) : null,
                ];

                $this->personaModel->insert($datosPersona);
                $personaId = $this->personaModel->getInsertID();
            } else {
                // CASO B: Ya existía la persona base, actualizamos su correo si se modificó
                if (!empty($data['per_correo'])) {
                    $this->personaModel->update($personaId, ['per_correo' => trim($data['per_correo'])]);
                }
            }

            // Inserción en la tabla estructural de Personal Laboral
            $datosLaborales = [
                'persona_id'                => $personaId,
                'oficina_id'                => $data['oficina_id'],
                'cargo_id'                  => $data['cargo_id'],
                'profesion_id'              => $data['profesion_id'],
                'se_ide'                    => !empty($data['se_ide']) ? $data['se_ide'] : null, // Segunda Especialidad
                'regimen_id'                => $data['regimen_id'],
                'psn_fecha_ingreso'         => $data['psn_fecha_ingreso'],
                'psn_documento_vinculo'     => !empty($data['psn_documento_vinculo']) ? trim(mb_strtoupper($data['psn_documento_vinculo'])) : null,
                'psn_codigo_biometrico'     => trim($data['psn_codigo_biometrico']),
                'psn_exonerado_asistencia'  => $data['psn_exonerado_asistencia'] ?? 0,
                'psn_estado'                => 1, // Alta directa activa
                'created_at'                => date('Y-m-d H:i:s')
            ];

            $this->personalModel->insert($datosLaborales);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return ['error' => 'Fallo crítico al asentar la transacción en la base de datos.'];
            }

            return true;
        } catch (Exception $e) {
            $this->db->transRollback();
            return ['error' => 'Excepción en el proceso: ' . $e->getMessage()];
        }
    }

    /**
     * Validador interno para mitigar inyecciones o inconsistencias de datos requeridos
     */
    private function validarPayloadPersonal(array $data): array
    {
        $validationErrors = [];

        // Validación de campos biométricos obligatorios
        if (empty($data['psn_codigo_biometrico'])) {
            $validationErrors['psn_codigo_biometrico'] = 'El código de marcación biométrica es mandatorio.';
        } else {
            // Verificar duplicidad de ID Biométrico para que no se crucen las marcaciones en los relojes
            $existeCodigo = $this->personalModel->where('psn_codigo_biometrico', trim($data['psn_codigo_biometrico']))->first();
            if ($existeCodigo) {
                $validationErrors['psn_codigo_biometrico'] = 'El código biométrico ingresado ya está asignado a otro trabajador.';
            }
        }

        // Validación de llaves foráneas requeridas
        if (empty($data['oficina_id'])) $validationErrors['oficina_id'] = 'Debe definir una oficina destino.';
        if (empty($data['cargo_id'])) $validationErrors['cargo_id'] = 'Debe definir un cargo estructural.';
        if (empty($data['profesion_id'])) $validationErrors['profesion_id'] = 'Debe asignar la profesión base.';
        if (empty($data['psn_fecha_ingreso'])) $validationErrors['psn_fecha_ingreso'] = 'La fecha de ingreso institucional es requerida.';

        return $validationErrors;
    }
}
