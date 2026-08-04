<?php

namespace Modules\Asistencia\Services;

use Modules\Asistencia\Models\RegistroPermisoModel;
use Modules\Asistencia\Models\RegistroPermisoHistorialModel;
use Modules\Asistencia\Models\AdjuntoModel;
use Modules\Asistencia\Services\PeriodoService;
use Exception;

class RegistroPermisoService
{
    protected $registroPermisoModel;
    protected $historialModel;
    protected $adjuntoModel;
    protected $db;

    protected $periodoService;
    public function __construct()
    {
        $this->registroPermisoModel = new RegistroPermisoModel();
        $this->historialModel        = new RegistroPermisoHistorialModel();
        $this->adjuntoModel         = new AdjuntoModel();
        $this->db                   = \Config\Database::connect();
        $this->periodoService       = new PeriodoService();
    }

    /**
     * Obtiene los permisos registrados de un trabajador, incluyendo el catálogo (pero_) y sus adjuntos.
     */
    public function obtenerPermisosPorPersonal(int $perlIde, ?string $mes = null, ?string $anio = null): array
    {
        $permisos = $this->registroPermisoModel->obtenerPorPersonalConTipo($perlIde, $mes, $anio);

        foreach ($permisos as &$item) {
            $item['adjuntos'] = $this->adjuntoModel
                ->where('adj_modulo', 'PERMISO')
                ->where('adj_registro_id', $item['rp_ide'])
                ->orderBy('adj_orden', 'ASC')
                ->findAll();
        }

        return $permisos;
    }

    /**
     * Registra un permiso/papeleta con sus archivos adjuntos e historial de auditoría.
     */
    public function crearPermiso(array $data, $archivos, string $ip, int $usuarioId): int
    {

        $perlIde = (int) ($data['rp_perl_ide'] ?? 0);
        $fecha   = $data['rp_fecha'] ?? date('Y-m-d');

        $this->periodoService->validarPermisoPeriodo($perlIde, $fecha);

        $this->db->transException(true)->transBegin();

        try {
            $rpIde = $this->registroPermisoModel->insert($data);

            if (!$rpIde) {
                $erroresModelo = implode(', ', $this->registroPermisoModel->errors());
                throw new Exception("Error de validación en RegistroPermisoModel: " . $erroresModelo);
            }

            // Procesar archivos subidos
            if (!empty($archivos)) {
                $filesToProcess = is_array($archivos) ? $archivos : [$archivos];
                $orden = 1;

                foreach ($filesToProcess as $file) {
                    if ($file && $file->isValid() && !$file->hasMoved()) {
                        $newName   = $file->getRandomName();
                        $relativePath = 'uploads/permisos/' . $newName;

                        $file->move(WRITEPATH . 'uploads/permisos', $newName);

                        $insertAdjunto = $this->adjuntoModel->insert([
                            'adj_modulo'          => 'PERMISO',
                            'adj_registro_id'     => $rpIde,
                            'adj_local_path'      => $relativePath,
                            'adj_drive_path'      => null,
                            'adj_nombre_original' => $file->getClientName(),
                            'adj_mime_type'       => $file->getClientMimeType(),
                            'adj_tamano'          => $file->getSize(),
                            'adj_orden'           => $orden++,
                            'created_by'          => $usuarioId
                        ]);

                        if (!$insertAdjunto) {
                            $erroresAdj = implode(', ', $this->adjuntoModel->errors());
                            throw new Exception("Error al insertar en AdjuntoModel: " . $erroresAdj);
                        }
                    }
                }
            }

            // Historial de auditoría inicial
            $insertHistorial = $this->historialModel->insert([
                'rph_rp_ide'     => $rpIde,
                'rph_accion'     => 'CREAR',
                'rph_datos_anteriores' => null,
                'rph_datos_nuevos'     => json_encode($data),
                'rph_motivo_cambio'    => 'Registro inicial de la papeleta/permiso por horas.',
                'rph_ip'         => $ip,
                'created_by'     => $usuarioId,
                'created_at'     => date('Y-m-d H:i:s')
            ]);

            if (!$insertHistorial) {
                $erroresHist = implode(', ', $this->historialModel->errors());
                throw new Exception("Error al guardar en HistorialModel: " . $erroresHist);
            }

            $this->db->transCommit();

            return $rpIde;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            throw new Exception("Fallo transaccional: " . $e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    /**
     * Aplica borrado lógico (Soft Delete) del permiso guardando motivo y usuario que elimina.
     */
    public function eliminarPermiso(int $rpIde, int $usuarioId, string $ip, string $motivo): bool
    {
        $permiso = $this->registroPermisoModel->find($rpIde);

        if (!$permiso) {
            throw new Exception("No se encontró el registro de licencia especificado.", 404);
        }

        // 2. Validar disponibilidad del período de corte asociado a la fecha de la licencia
        $perlIde = (int) ($permiso['rp_perl_ide'] ?? 0);
        $fecha   = $permiso['rp_fecha'] ?? date('Y-m-d');

        $this->periodoService->validarPermisoPeriodo($perlIde, $fecha);


        // Activar excepciones en transacciones y comenzar de forma manual
        $this->db->transException(true)->transBegin();

        try {
            // 1. Guardar motivo de cambio y usuario que elimina antes del soft delete
            $updateResult = $this->registroPermisoModel->update($rpIde, [
                'rph_motivo_cambio' => $motivo,
                'deleted_by'       => $usuarioId
            ]);

            if (!$updateResult) {
                $erroresModel = implode(', ', $this->registroPermisoModel->errors());
                throw new Exception("Error al actualizar motivo/usuario en RegistroPermisoModel: " . $erroresModel);
            }

            // 2. Ejecutar Soft Delete (llena automáticamente deleted_at)
            $deleteResult = $this->registroPermisoModel->delete($rpIde);

            if (!$deleteResult) {
                $erroresDelete = implode(', ', $this->registroPermisoModel->errors());
                throw new Exception("Error al ejecutar delete en RegistroPermisoModel: " . $erroresDelete);
            }

            // 3. Registrar auditoría de eliminación
            $insertHistorial = $this->historialModel->insert([
                'rph_rp_ide'     => $rpIde,
                'rph_accion'     => 'ELIMINACION',
                'rph_detalle'    => 'Motivo: ' . $motivo,
                'rph_ip'         => $ip,
                'rph_usuario_id' => $usuarioId,
                'created_at'     => date('Y-m-d H:i:s')
            ]);

            if (!$insertHistorial) {
                $erroresHist = implode(', ', $this->historialModel->errors());
                throw new Exception("Error al insertar auditoría en HistorialModel: " . $erroresHist);
            }

            // Confirmar transacción
            $this->db->transCommit();

            return true;
        } catch (\Throwable $e) {
            // Revertir cambios en caso de cualquier error (base de datos o código)
            $this->db->transRollback();

            throw new Exception("Fallo al eliminar permiso: " . $e->getMessage(), (int)$e->getCode(), $e);
        }
    }
}
