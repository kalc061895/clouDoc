<?php

namespace Modules\Asistencia\Services;

use Modules\Asistencia\Models\RegistroLicenciaModel;
use Modules\Asistencia\Models\RegistroLicenciaHistorialModel;
use Modules\Asistencia\Models\AdjuntoModel;

class RegistroLicenciaService
{
    protected $registroLicenciaModel;
    protected $historialModel;
    protected $adjuntoModel;
    protected $fileUploadService;
    protected $db;

    public function __construct()
    {
        $this->registroLicenciaModel = new RegistroLicenciaModel();
        $this->historialModel        = new RegistroLicenciaHistorialModel();
        $this->adjuntoModel          = new AdjuntoModel();
        $this->fileUploadService     = new FileUploadService();
        $this->db                    = \Config\Database::connect();
    }

    /**
     * Registrar papeleta/licencia con opción de 0, 1 o múltiples adjuntos
     */
    public function registrarLicencia(array $datos, $archivosHttp = null, ?int $usuarioId = null, ?string $ip = null): bool|array
    {
        $usuarioId = $usuarioId ?? session()->get('user_id') ?? session()->get('usu_ide') ?? 1;
        $ip        = $ip ?? service('request')->getIPAddress();

        $dataInsert = [
            'rl_lic_ide'          => $datos['rl_lic_ide'] ?? null,
            'rl_perl_ide'         => $datos['rl_perl_ide'] ?? null,
            'rl_motivo'           => $datos['rl_motivo'] ?? null,
            'rl_fecha_inicio'     => $datos['rl_fecha_inicio'] ?? null,
            'rl_fecha_fin'        => $datos['rl_fecha_fin'] ?? null,
            'rl_justificacion'    => $datos['rl_justificacion'] ?? null,
            'rl_numero_documento' => $datos['rl_numero_documento'] ?? null,
            'rl_fecha_documento'  => !empty($datos['rl_fecha_documento']) ? $datos['rl_fecha_documento'] : null,
            'rl_estado'           => $datos['rl_estado'] ?? 1,
            'created_by'          => $usuarioId,
        ];

        $this->db->transStart();

        // 1. Guardar la licencia
        if ($this->registroLicenciaModel->insert($dataInsert) === false) {
            $this->db->transRollback();
            return $this->registroLicenciaModel->errors();
        }

        $rlIde = $this->registroLicenciaModel->getInsertID();

        // 2. Procesar y guardar los adjuntos (si existen) usando el servicio
        $adjuntosGuardados = [];
        if (!empty($archivosHttp)) {
            $adjuntosGuardados = $this->fileUploadService->procesarYGuardar(
                $archivosHttp,
                'LICENCIA',
                $rlIde,
                'asistencia/licencias',
                $usuarioId
            );
        }

        // 3. Auditoría
        $dataInsert['adjuntos'] = $adjuntosGuardados;

        $this->historialModel->insert([
            'his_rl_ide'           => $rlIde,
            'his_accion'           => 'CREAR',
            'his_datos_anteriores' => null,
            'his_datos_nuevos'     => json_encode($dataInsert),
            'his_motivo_cambio'    => $datos['his_motivo_cambio'] ?? 'Registro de licencia/papeleta',
            'his_ip'               => $ip,
            'created_by'           => $usuarioId,
        ]);

        $this->db->transComplete();

        return $this->db->transStatus() !== false;
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
     * @param array $datosInsert
     * @param array|null $archivos Archivos recibidos desde $request->getFiles() o $request->getFileMultiple()
     * @param string $ip
     * @param int $usuarioId
     * @return int ID de la licencia creada
     * @throws \Exception
     */
    public function crearLicencia(array $datosInsert, $archivos, string $ip, int $usuarioId): int
    {
        $this->db->transStart();

        try {
            // 1. Insertar Licencia
            $this->registroLicenciaModel->insert($datosInsert);
            $rlIde = $this->registroLicenciaModel->getInsertID();

            // 2. Registrar Auditoría
            $this->historialModel->insert([
                'his_rl_ide'           => $rlIde,
                'his_accion'           => 'CREAR',
                'his_datos_anteriores' => null,
                'his_datos_nuevos'     => json_encode($datosInsert),
                'his_motivo_cambio'    => 'Registro inicial de licencia/papeleta',
                'his_ip'               => $ip,
                'created_by'           => $usuarioId
            ]);

            // 3. Procesar y guardar Archivos Adjuntos
            $this->procesarAdjuntos($archivos, $rlIde, $usuarioId);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new DatabaseException('Error al completar la transacción de guardado de licencia.');
            }

            return $rlIde;
        } catch (\Exception $e) {
            $this->db->transRollback();
            throw $e;
        }
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
