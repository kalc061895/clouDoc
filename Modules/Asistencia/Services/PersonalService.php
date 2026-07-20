<?php

namespace Modules\Asistencia\Services;

// Importamos los modelos necesarios
use Modules\Asistencia\Models\TipoDocumentoModel;
use Modules\Asistencia\Models\EstablecimientoModel;
use Modules\Asistencia\Models\ModalidadContratoModel;
use Modules\Asistencia\Models\CargoModel;
use Modules\Asistencia\Models\ProfesionModel;
use Modules\Asistencia\Models\ColegiaturaModel;
use Modules\Asistencia\Models\ProfesionEspecialidadModel;
use Modules\Asistencia\Models\UnidadOrganizacionalModel;
use Modules\Asistencia\Models\OficinaModel;

use Config\Database;

use Modules\Asistencia\Models\PersonaModel;
use Modules\Asistencia\Models\PersonalModel;
use Modules\Asistencia\Models\PersonalProfesionalModel;

use CodeIgniter\Database\Exceptions\DatabaseException;

class PersonalService
{
    /**
     * Helper genérico para transformar colecciones al estándar del Wizard
     */
    private function mapearSelect(array $coleccion, string $campoId, string $campoNombre): array
    {
        return array_map(function ($item) use ($campoId, $campoNombre) {
            $id = is_array($item) ? $item[$campoId] : $item->$campoId;
            $nombre = is_array($item) ? $item[$campoNombre] : $item->$campoNombre;

            return [
                'id' => $id,
                'nombre' => esc(strtoupper($nombre))
            ];
        }, $coleccion);
    }

    public function getSelectTiposDocumento(): array
    {
        $model = new TipoDocumentoModel();
        return $this->mapearSelect($model->where('tdi_estado', 1)->findAll(), 'tdi_ide', 'tdi_abreviatura');
    }

    public function getSelectEstablecimientos(): array
    {
        $model = new EstablecimientoModel();
        return $this->mapearSelect($model->orderBy('est_nombre', 'ASC')->findAll(), 'est_ide', 'est_nombre');
    }

    public function getSelectModalidadesContrato(): array
    {
        $model = new ModalidadContratoModel();
        return $this->mapearSelect($model->findAll(), 'mco_ide', 'mco_nombre');
    }

    public function getSelectCargos(): array
    {
        $model = new CargoModel();
        return $this->mapearSelect($model->orderBy('car_nombre', 'ASC')->findAll(), 'car_ide', 'car_nombre');
    }

    public function getSelectProfesiones(): array
    {
        $model = new ProfesionModel();
        return $this->mapearSelect($model->orderBy('pro_nombre', 'ASC')->findAll(), 'pro_ide', 'pro_nombre');
    }



    public function getSelectUnidades(int $estIde): array
    {
        $model = new UnidadOrganizacionalModel();
        $datos = $model->where('uo_est_ide', $estIde)->orderBy('uo_nombre', 'ASC')->findAll();
        return $this->mapearSelect($datos, 'uo_ide', 'uo_nombre');
    }

    public function getSelectOficinas(int $estIde): array
    {
        $model = new OficinaModel();
        $datos = $model->where('ofi_est_ide', $estIde)->orderBy('ofi_nombre', 'ASC')->findAll();
        return $this->mapearSelect($datos, 'ofi_ide', 'ofi_nombre');
    }
    /**
     * Registra de manera atómica y transaccional un nuevo miembro del personal
     * 
     * @param array $datos Formulario serializado proveniente del Wizard
     * @return array Respuesta de estado con el resultado de la operación
     * @throws \Exception Si ocurre alguna falla en las validaciones de negocio
     */
    public function guardarPersonalWizard(array $datos): array
    {
        $db = Database::connect();
        $msg = '';
        // Iniciamos el bloque de Transacción Segura
        $db->transException(true)->transStart();

        try {
            // ----------------------------------------------------------------
            // PASO 1: Inserción en 'casis_persona'
            // ----------------------------------------------------------------
            $personaModel = new PersonaModel();

            $dataPersona = [
                'per_tdi_ide' => $datos['per_tdi_ide'],
                'per_numero_documento' => trim($datos['per_numero_documento']),
                'per_ruc' => !empty($datos['per_ruc']) ? trim($datos['per_ruc']) : null,
                'per_paterno' => !empty($datos['per_paterno']) ? strtoupper(trim($datos['per_paterno'])) : null,
                'per_materno' => !empty($datos['per_materno']) ? strtoupper(trim($datos['per_materno'])) : null,
                'per_nombre' => strtoupper(trim($datos['per_nombre'])),
                'per_sexo' => !empty($datos['per_sexo']) ? $datos['per_sexo'] : null,
                'per_fecha_nacimiento' => !empty($datos['per_fecha_nacimiento']) ? $datos['per_fecha_nacimiento'] : null,
                'per_telefono' => !empty($datos['per_telefono']) ? trim($datos['per_telefono']) : null,
                'per_email' => !empty($datos['per_email']) ? trim($datos['per_email']) : null,
                'per_lugar_nacimiento' => !empty($datos['per_lugar_nacimiento']) ? strtoupper(trim($datos['per_lugar_nacimiento'])) : null,
                'per_residencia' => !empty($datos['per_residencia']) ? strtoupper(trim($datos['per_residencia'])) : null,
                'per_estadocivil' => !empty($datos['per_estadocivil']) ? $datos['per_estadocivil'] : null,
                'per_estado' => 1 // Activo por defecto
            ];

            // Insertamos y capturamos el ID autogenerado
            if (!$personaModel->insert($dataPersona)) {
                throw new \Exception("Error al registrar los datos personales: " . implode(', ', $personaModel->errors()));
            }

            $personaId = $personaModel->getInsertID();

            $msg .= "Persona registrada con ID: $personaId. ";
            // ----------------------------------------------------------------
            // PASO 2: Inserción en 'casis_personal' (Relación Laboral)
            // ----------------------------------------------------------------
            $personalModel = new PersonalModel();

            $dataPersonal = [
                'perl_per_ide' => $personaId, // Relación foránea directa con la persona recién creada
                'perl_codigo' => trim($datos['perl_codigo']),
                'perl_est_ide' => $datos['perl_est_ide'],
                'perl_mco_ide' => $datos['perl_mco_ide'],
                'perl_ofi_ide' => !empty($datos['perl_ofi_ide']) ? $datos['perl_ofi_ide'] : null,
                'perl_car_ide' => !empty($datos['perl_car_ide']) ? $datos['perl_car_ide'] : null,
                'perl_fecha_inicio' => $datos['perl_fecha_inicio'],
                'perl_fecha_termino' => !empty($datos['perl_fecha_termino']) ? $datos['perl_fecha_termino'] : null,
                'perl_plaza' => !empty($datos['perl_plaza']) ? trim($datos['perl_plaza']) : null,
                'perl_nivel' => !empty($datos['perl_nivel']) ? trim($datos['perl_nivel']) : null,
                'perl_observacion' => !empty($datos['perl_observacion']) ? trim($datos['perl_observacion']) : null,
                'perl_estado' => 1
            ];

            if (!$personalModel->insert($dataPersonal)) {
                throw new \Exception("Error al registrar el perfil laboral: " . implode(', ', $personalModel->errors()));
            }

            $personalId = $personalModel->getInsertID();
            $msg .= "Perfil laboral registrado con ID: $personalId. ";

            // ----------------------------------------------------------------
            // PASO 3: Inserción en 'casis_personal_profesion'
            // ----------------------------------------------------------------
            $profesionModel = new PersonalProfesionalModel();

            $dataProfesion = [
                'pp_perl_ide' => $personalId, // Relación foránea con casis_personal
                'pp_pro_ide' => $datos['pp_pro_ide'],
                'pp_col_ide' => !empty($datos['pp_col_ide']) ? $datos['pp_col_ide'] : null,
                'pp_numero_colegiatura' => !empty($datos['pp_numero_colegiatura']) ? trim($datos['pp_numero_colegiatura']) : null,
                'pp_se_ide' => !empty($datos['pp_se_ide']) ? $datos['pp_se_ide'] : null,
                'pp_rne' => !empty($datos['pp_rne']) ? trim($datos['pp_rne']) : null,
                'pp_habilitado' => isset($datos['pp_habilitado']) ? $datos['pp_habilitado'] : 1,
                'pp_fecha_habilitacion' => !empty($datos['pp_fecha_habilitacion']) ? $datos['pp_fecha_habilitacion'] : null,
                'pp_fecha_vencimiento' => !empty($datos['pp_fecha_vencimiento']) ? $datos['pp_fecha_vencimiento'] : null,
                'pp_estado' => 1
            ];

            if (!$profesionModel->insert($dataProfesion)) {
                throw new \Exception("Error al registrar el historial profesional: " . implode(', ', $profesionModel->errors()));
            }

            $msg .= "Historial profesional registrado con ID: " . $profesionModel->getInsertID() . ". ";

            // Si todo salió bien, confirmamos permanentemente en la base de datos
            $db->transComplete();
            
            // 3. Verificamos el estado final de la transacción
            if ($db->transStatus() === false) {
                // Obtenemos el último error de la base de datos
                $error = $db->error();

                return [
                    'status' => 'error',
                    'message' => $msg.'Error ssssen la base de datos: ' . $error['message'],
                    'code' => $error['code'] // Código numérico del error (ej. 1452 para llaves foráneas)
                ];
            }

            return [
                'status' => 'success',
                'message' => $msg . 'El alta del personal ha sido procesada con éxito.'
            ];

        } catch (\Exception $e) {
            // Si algo falla, se dispara el Rollback inmediato desaciendo los inserts previos
            $db->transRollback();

            return [
                'status' => 'error',
                'message' => $msg . $e->getMessage()
            ];
        }


    }

    public function getSelectColegios(?int $proIde = null): array
    {
        $model = new ColegiaturaModel();

        // Si se envía profesión, filtramos los colegios vinculados a ella
        if ($proIde !== null) {
            $model->where('col_pro_ide', $proIde); // Asumiendo que col_pro_ide es tu llave foránea
        }

        return $this->mapearSelect($model->orderBy('col_nombre', 'ASC')->findAll(), 'col_ide', 'col_nombre');
    }

    public function getSelectEspecialidades(?int $proIde = null): array
    {
        $model = new ProfesionEspecialidadModel();

        if ($proIde !== null) {

            return $this->mapearSelect($model->obtenerEspecialidadesPorProfesion($proIde), 'se_ide', 'se_nombre');
        }

        return $this->mapearSelect($model->orderBy('se_nombre', 'ASC')->findAll(), 'se_ide', 'se_nombre');
    }

    /**
     * Obtiene el listado completo de personal activo y cesado
     */
    public function getListadoPersonal(): array
    {
        $db = Database::connect();
        $builder = $db->table('casis_personal as pl');
        $builder->select('
            pl.perl_ide as id,
            pl.perl_codigo as codigo,
            p.per_numero_documento as documento,
            CONCAT(p.per_paterno, " ", p.per_materno, ", ", p.per_nombre) as nombre_completo,
            est.est_nombre as establecimiento,
            car.car_nombre as cargo,
            pl.perl_estado as estado
        ');
        $builder->join('casis_persona p', 'p.per_ide = pl.perl_per_ide');
        $builder->join('casis_establecimiento est', 'est.est_ide = pl.perl_est_ide');
        $builder->join('casis_cargo car', 'car.car_ide = pl.perl_car_ide', 'left');
        $builder->orderBy('p.per_paterno', 'ASC');

        return $builder->get()->getResultArray();
    }

    /**
     * Obtiene los datos completos de un personal para su edición
     */
    public function getPersonalParaEditar(int $id): ?array
    {
        $db = \Config\Database::connect();
        $builder = $db->table('casis_personal as pl');
        $builder->select('
        pl.*, 
        p.per_tdi_ide, p.per_numero_documento, p.per_nombre, 
        p.per_paterno, p.per_materno, p.per_sexo, p.per_fecha_nacimiento
    ');
        $builder->join('casis_persona p', 'p.per_ide = pl.perl_per_ide');
        $builder->where('pl.perl_ide', $id);

        return $builder->get()->getRowArray();
    }

    /**
     * Da de baja lógica a un miembro del personal
     */
    public function darDeBajaPersonal(int $id): bool
    {
        $personalModel = new PersonalModel();

        // Hacemos un update al estado (0 = Inactivo / Baja)
        return $personalModel->update($id, [
            'perl_estado' => 0,
            'perl_fecha_termino' => date('Y-m-d') // Opcional: setea el cese hoy
        ]);
    }

    /**
     * Actualiza el expediente del personal y genera un respaldo histórico del estado anterior.
     */
    public function actualizarPersonalConHistorial(int $perlIde, array $datosPost): bool
    {
        $db = Database::connect();
        $personalModel = new PersonalModel();
        $personaModel = new PersonaModel();

        // 1. Iniciar Transacción para asegurar consistencia atómica
        $db->transStart();

        // 2. Obtener la data actual (ANTES de modificarla) para el historial
        $estadoAnterior = $db->table('casis_personal as pl')
            ->select('pl.*, p.per_tdi_ide, p.per_numero_documento, p.per_nombre, p.per_paterno, p.per_materno, p.per_sexo, p.per_fecha_nacimiento')
            ->join('casis_persona p', 'p.per_ide = pl.perl_per_ide')
            ->where('pl.perl_ide', $perlIde)
            ->get()
            ->getRowArray();

        if (!$estadoAnterior) {
            return false;
        }

        // 3. Insertar la instantánea en la tabla de historial
        // Guardamos toda la data anterior + metadatos de auditoría
        $db->table('casis_personal_historial')->insert([
            'h_perl_ide' => $estadoAnterior['perl_ide'],
            'h_perl_codigo' => $estadoAnterior['perl_codigo'],
            'h_perl_per_ide' => $estadoAnterior['perl_per_ide'],
            'h_perl_est_ide' => $estadoAnterior['perl_est_ide'],
            'h_perl_ofi_ide' => $estadoAnterior['perl_ofi_ide'],
            'h_perl_car_ide' => $estadoAnterior['perl_car_ide'],
            'h_perl_mco_ide' => $estadoAnterior['perl_mco_ide'],
            'h_perl_fecha_inicio' => $estadoAnterior['perl_fecha_inicio'],
            'h_pp_pro_ide' => $estadoAnterior['pp_pro_ide'],
            'h_pp_col_ide' => $estadoAnterior['pp_col_ide'],
            'h_pp_numero_colegiatura' => $estadoAnterior['pp_numero_colegiatura'],
            'h_pp_se_ide' => $estadoAnterior['pp_se_ide'],
            'h_pp_rne' => $estadoAnterior['pp_rne'],
            'h_perl_estado' => $estadoAnterior['perl_estado'],
            // Campos de control de auditoría
            'h_fecha_modificacion' => date('Y-m-d H:i:s'),
            'h_usuario_modifica' => session()->get('usu_ide') ?? 1 // ID del usuario en sesión
        ]);

        // 4. Preparar y actualizar la tabla base: Persona (`casis_persona`)
        $dataPersona = [
            'per_tdi_ide' => $datosPost['per_tdi_ide'],
            'per_numero_documento' => $datosPost['per_numero_documento'],
            'per_nombre' => $datosPost['per_nombre'],
            'per_paterno' => $datosPost['per_paterno'],
            'per_materno' => $datosPost['per_materno'],
            'per_sexo' => $datosPost['per_sexo'],
            'per_fecha_nacimiento' => $datosPost['per_fecha_nacimiento'],
        ];
        $personaModel->update($estadoAnterior['perl_per_ide'], $dataPersona);

        // 5. Preparar y actualizar la tabla operativa: Personal (`casis_personal`)
        // Manejo controlado de los campos opcionales del paso 3 del Wizard (Colegiaturas)
        $proIde = !empty($datosPost['pp_pro_ide']) ? (int) $datosPost['pp_pro_ide'] : null;

        $dataPersonal = [
            'perl_codigo' => !empty($datosPost['perl_codigo']) ? strtoupper($datosPost['perl_codigo']) : null,
            'perl_mco_ide' => $datosPost['perl_mco_ide'],
            'perl_car_ide' => $datosPost['perl_car_ide'],
            'perl_est_ide' => $datosPost['perl_est_ide'],
           
            'perl_ofi_ide' => !empty($datosPost['perl_ofi_ide']) ? $datosPost['perl_ofi_ide'] : null,
            'perl_fecha_inicio' => $datosPost['perl_fecha_inicio'],

            // Lógica e integridad de profesión/colegio
            'pp_pro_ide' => $proIde,
            'pp_col_ide' => ($proIde && !empty($datosPost['pp_col_ide'])) ? $datosPost['pp_col_ide'] : null,
            'pp_numero_colegiatura' => ($proIde && !empty($datosPost['pp_numero_colegiatura'])) ? $datosPost['pp_numero_colegiatura'] : null,
            'pp_se_ide' => ($proIde && !empty($datosPost['pp_se_ide'])) ? $datosPost['pp_se_ide'] : null,
            'pp_rne' => ($proIde && !empty($datosPost['pp_rne'])) ? $datosPost['pp_rne'] : null,
        ];
        $personalModel->update($perlIde, $dataPersonal);

        // 6. Confirmar transacciones
        $db->transComplete();

        return $db->transStatus();
    }
}