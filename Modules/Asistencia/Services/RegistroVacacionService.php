<?php

namespace Modules\Asistencia\Services;

use Modules\Asistencia\Models\RegistroVacacionModel;
use Modules\Asistencia\Models\VacacionAnexoModel;
use Modules\Asistencia\Models\VacacionAuditoriaModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class RegistroVacacionService
{
    protected $vacacionModel;
    protected $anexoModel;
    protected $auditoriaModel;
    protected $db;

    public function __construct()
    {
        $this->vacacionModel = new RegistroVacacionModel();
        $this->anexoModel = new VacacionAnexoModel();
        $this->auditoriaModel = new VacacionAuditoriaModel();
        $this->db = \Config\Database::connect();
    }

    /**
     * Obtener vacaciones registradas por personal, filtrado opcional por mes y año.
     */
    public function obtenerVacacionesPorPersonal(int $perlIde, ?string $mes = null, ?string $anio = null): array
    {
        return $this->vacacionModel->obtenerPorPersonal($perlIde, $mes, $anio);
    }

    /**
     * Crear un nuevo registro de vacación y procesar los anexos cargados.
     * 
     * @param array $datos
     * @param mixed $archivos Array de HTTPFile object / HTTPFile object / null
     * @param string $ip
     * @param int $usuarioId
     * @return int ID de la vacación creada
     * @throws \Exception
     */
    public function crearVacacion(array $datos, $archivos = null, string $ip = '', int $usuarioId = 1): int
    {
        $this->db->transStart();

        try {
            // 1. Insertar el registro principal de la vacación
            $rvIde = $this->vacacionModel->insert($datos);

            if (!$rvIde) {
                throw new \Exception('No se pudo guardar el registro de vacación.');
            }

            // 2. Procesar y guardar anexos si existen
            if (!empty($archivos)) {
                $this->subirAnexos($rvIde, $archivos, $usuarioId);
            }

            // 3. Registrar auditoría de creación
            $this->auditoriaModel->insert([
                'rva_rv_ide' => $rvIde,
                'rva_accion' => 'CREAR',
                'rva_motivo_cambio' => 'Registro inicial de vacación / licencia',
                'rva_ip' => $ip,
                'created_by' => $usuarioId
            ]);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new DatabaseException('Error al completar la transacción de creación.');
            }

            return $rvIde;
        } catch (\Exception $e) {
            $this->db->transRollback();
            throw new \Exception('Error al registrar vacación: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar (o desactivar) un registro de vacación con motivo de auditoría.
     */
    public function eliminarVacacion(int $rvIde, int $usuarioId, string $ip, string $motivoCambio): bool
    {
        $vacacion = $this->vacacionModel->find($rvIde);

        if (!$vacacion || (isset($vacacion['rv_estado']) && $vacacion['rv_estado'] == 0)) {
            throw new \Exception('El registro de vacación no existe o ya fue eliminado.', 404);
        }

        $this->db->transStart();

        try {
            // Modificar estado a inactivo (0) o soft delete
            $this->vacacionModel->update($rvIde, [
                'rv_estado' => 0,
                'updated_by' => $usuarioId
            ]);

            // Registrar en tabla de auditoría
            $this->auditoriaModel->insert([
                'rva_rv_ide' => $rvIde,
                'rva_accion' => 'ELIMINAR',
                'rva_motivo_cambio' => $motivoCambio,
                'rva_ip' => $ip,
                'created_by' => $usuarioId
            ]);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new DatabaseException('Error en la transacción al eliminar la vacación.');
            }

            return true;
        } catch (\Exception $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    /**
     * Manejo de subida física y almacenamiento en BD de los archivos adjuntos.
     */
    protected function subirAnexos(int $rvIde, $archivos, int $usuarioId): void
    {
        $files = is_array($archivos) ? $archivos : [$archivos];

        $uploadPath = WRITEPATH . 'uploads/vacaciones/' . date('Y/m');

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        foreach ($files as $file) {
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move($uploadPath, $newName);

                $this->anexoModel->insert([
                    'rva_rv_ide' => $rvIde,
                    'rva_nombre_orig' => $file->getClientName(),
                    'rva_ruta' => 'uploads/vacaciones/' . date('Y/m') . '/' . $newName,
                    'rva_mime' => $file->getClientMimeType(),
                    'rva_tamano' => $file->getSize(),
                    'created_by' => $usuarioId
                ]);
            }
        }
    }
}