<?php

namespace Modules\Asistencia\Services;

use Modules\Asistencia\Models\RegistroLicenciaModel;
use Modules\Asistencia\Models\RegistroLicenciaHistorialModel;
use Modules\Asistencia\Models\AdjuntoModel;
use Modules\Asistencia\Services\PeriodoService;

class RegistroLicenciaService
{
    protected $registroLicenciaModel;
    protected $historialModel;
    protected $adjuntoModel;
    protected $fileUploadService;
    protected $db;

    protected $periodoService;

    public function __construct()
    {
        $this->registroLicenciaModel = new RegistroLicenciaModel();
        $this->historialModel        = new RegistroLicenciaHistorialModel();
        $this->adjuntoModel          = new AdjuntoModel();
        $this->fileUploadService     = new FileUploadService();
        $this->periodoService        = new PeriodoService();
        $this->db                    = \Config\Database::connect();
    }

    /**
     * Obtener una licencia con sus adjuntos cargados
     */
    public function obtenerLicenciaConAdjuntos(int $rlIde): ?array
    {
        $licencia = $this->registroLicenciaModel->find($rlIde);
        if (!$licencia) {
            return null;
        }

        $licencia['adjuntos'] = $this->adjuntoModel->obtenerPorRegistro('LICENCIA', $rlIde);
        return $licencia;
    }
    /**
     * Obtener el listado de licencias por personal con filtros opcionales de mes y año
     * 
     * @param int $perlIde
     * @param string|null $mes
     * @param string|null $anio
     * @return array
     */
    /**
     * Obtener el listado de licencias por personal con sus adjuntos polimórficos
     */
    public function obtenerLicenciasPorPersonal(int $perlIde, ?string $mes = null, ?string $anio = null): array
    {
        $anio = $anio ?? date('Y');

        $builder = $this->registroLicenciaModel
            ->select('casis_registro_licencia.*, casis_licencia.lic_nombre, casis_licencia.lic_abreviatura, casis_licencia.lic_remunerado')
            ->join('casis_licencia', 'casis_licencia.lic_ide = casis_registro_licencia.rl_lic_ide')
            ->where('casis_registro_licencia.rl_perl_ide', $perlIde);

        // Filtro opcional por Mes
        if (!empty($mes)) {
            $builder->where("DATE_FORMAT(casis_registro_licencia.rl_fecha_inicio, '%m')", str_pad($mes, 2, '0', STR_PAD_LEFT));
        }

        // Filtro por Año
        if (!empty($anio)) {
            $builder->where("DATE_FORMAT(casis_registro_licencia.rl_fecha_inicio, '%Y')", $anio);
        }

        $licencias = $builder->orderBy('casis_registro_licencia.rl_fecha_inicio', 'DESC')->findAll();

        // --- Carga de adjuntos si existen licencias ---
        if (!empty($licencias)) {
            // Extraer todos los IDs de las licencias (rl_ide)
            $licenciaIds = array_column($licencias, 'rl_ide');

            // Consultar adjuntos filtrando por módulo 'LICENCIA' y los IDs
            $adjuntos = $this->adjuntoModel
                ->where('adj_modulo', 'LICENCIA')
                ->whereIn('adj_registro_id', $licenciaIds)
                ->orderBy('adj_orden', 'ASC')
                ->findAll();

            // Agrupar los adjuntos por 'adj_registro_id'
            $adjuntosAgrupados = [];
            foreach ($adjuntos as $adj) {
                $adjuntosAgrupados[$adj['adj_registro_id']][] = $adj;
            }

            // Asignar el array de adjuntos a cada licencia
            foreach ($licencias as &$licencia) {
                $id = $licencia['rl_ide'];
                $licencia['adjuntos'] = $adjuntosAgrupados[$id] ?? [];
            }
        }

        return $licencias;
    }


    /**
     * Registrar una licencia, su auditoría y sus archivos adjuntos físicamente y en la BD.
     * 
     * @throws \Exception
     */
    public function crearLicencia(array $datosInsert, $archivos, string $ip, int $usuarioId): int
    {
        // Valida el período; si está cerrado/programado lanza la Exception automáticamente
        $this->periodoService->validarPermisoPeriodo((int) ($datosInsert['rl_perl_ide'] ?? 0), $datosInsert['rl_fecha_inicio'] ?? date('Y-m-d'));

        $this->db->transStart();

        try {
            // 3. Insertar Licencia
            if ($this->registroLicenciaModel->insert($datosInsert) === false) {
                $errores = implode(', ', $this->registroLicenciaModel->errors());
                throw new \Exception('Error de validación en el modelo: ' . $errores);
            }

            $rlIde = $this->registroLicenciaModel->getInsertID();

            // 4. Registrar Auditoría
            $this->historialModel->insert([
                'his_rl_ide'           => $rlIde,
                'his_accion'           => 'CREAR',
                'his_datos_anteriores' => null,
                'his_datos_nuevos'     => json_encode($datosInsert),
                'his_motivo_cambio'    => 'Registro inicial de licencia/papeleta',
                'his_ip'               => $ip,
                'created_by'           => $usuarioId
            ]);

            // 5. Procesar y guardar Archivos Adjuntos
            $this->procesarAdjuntos($archivos, $rlIde, $usuarioId);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Error al completar la transacción de guardado de licencia en la base de datos.');
            }

            return $rlIde;
        } catch (\Exception $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    /**
     * Elimina una licencia (Soft Delete), valida el período y registra la auditoría.
     *
     * @throws Exception Si el registro no existe, si el período no lo permite o por fallo en BD.
     */
    public function eliminarLicencia(int $rlIde, int $usuarioId, string $ip, string $motivoCambio = 'Eliminación del registro'): bool
    {
        // 1. Buscar registro antes de eliminar
        $licencia = $this->registroLicenciaModel->find($rlIde);

        if (!$licencia) {
            throw new \Exception("No se encontró el registro de licencia especificado.", 404);
        }

        // 2. Validar disponibilidad del período de corte asociado a la fecha de la licencia
        $perlIde = (int) ($licencia['rl_perl_ide'] ?? 0);
        $fecha   = $licencia['rl_fecha_inicio'] ?? date('Y-m-d');

        $this->periodoService->validarPermisoPeriodo($perlIde, $fecha);

        // 3. Iniciar Transacción
        $this->db->transStart();

        // Marcar usuario que realiza la eliminación
        $this->registroLicenciaModel->update($rlIde, ['deleted_by' => $usuarioId]);

        // Ejecutar Soft Delete (poblar deleted_at)
        $this->registroLicenciaModel->delete($rlIde);

        // Registrar Auditoría
        $this->historialModel->insert([
            'his_rl_ide'           => $rlIde,
            'his_accion'           => 'ELIMINAR',
            'his_datos_anteriores' => json_encode($licencia),
            'his_datos_nuevos'     => null,
            'his_motivo_cambio'    => $motivoCambio,
            'his_ip'               => $ip,
            'created_by'           => $usuarioId
        ]);

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            throw new \Exception("No se pudo completar la eliminación del registro en la base de datos.", 500);
        }

        return true;
    }
    /**
     * Procesa, mueve al disco y registra en BD los adjuntos recibidos
     */
    private function procesarAdjuntos($archivos, int $rlIde, int $usuarioId): void
    {
        if (empty($archivos)) {
            return;
        }

        // Si es un solo archivo envuelto o un array de archivos
        $files = is_array($archivos) ? $archivos : [$archivos];

        $orden = 1;
        $uploadPath = WRITEPATH . 'uploads/licencias/' . date('Y/m/');

        foreach ($files as $file) {
            // Verificar que el archivo sea válido y no haya sido movido
            if ($file && $file->isValid() && !$file->hasMoved()) {

                // Generar un nombre único para evitar sobreescrituras
                $newName = $file->getRandomName();
                $nombreOriginal = $file->getClientName();
                $mimeType = $file->getClientMimeType();
                $tamano = $file->getSize();

                // Mover archivo al servidor
                $file->move($uploadPath, $newName);

                // Insertar en casis_adjuntos
                $this->adjuntoModel->insert([
                    'adj_modulo'          => 'LICENCIA',
                    'adj_registro_id'     => $rlIde,
                    'adj_local_path'      => 'uploads/licencias/' . date('Y/m/') . $newName,
                    'adj_drive_path'      => '-', // Reservado para Google Drive más adelante
                    'adj_nombre_original' => $nombreOriginal,
                    'adj_mime_type'       => $mimeType,
                    'adj_tamano'          => $tamano,
                    'adj_orden'           => $orden++,
                    'created_by'          => $usuarioId
                ]);
            }
        }
    }
}
