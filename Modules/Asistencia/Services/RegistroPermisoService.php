<?php

namespace Modules\Asistencia\Services;
use Modules\Asistencia\Models\PermisoModel;
use Modules\Asistencia\Models\AdjuntoModel;



use CodeIgniter\Database\Exceptions\DatabaseException;

class RegistroPermisoService
{
    protected $db;
    protected $permisoModel;
    protected $adjuntoModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->permisoModel = new PermisoModel();
        $this->adjuntoModel = new AdjuntoModel();
    }

    /**
     * Obtiene el listado de permisos filtrado por personal, mes y año.
     */
    public function obtenerPermisosPorPersonal(int $perIde, ?int $mes = null, ?int $anio = null): array
    {
        $builder = $this->db->table('rh_permiso p')
            ->select('p.*, tp.tip_nombre as tipo_nombre, tp.tip_remunerado')
            ->join('rh_tipo_permiso tp', 'tp.tip_ide = p.tip_ide')
            ->where('p.per_ide', $perIde)
            ->where('p.perm_estado', 1);

        if ($mes) {
            $builder->where('MONTH(p.perm_fecha)', $mes);
        }

        if ($anio) {
            $builder->where('YEAR(p.perm_fecha)', $anio);
        }

        $permisos = $builder->orderBy('p.perm_fecha', 'DESC')->get()->getResultArray();

        // Adjuntar archivos correspondientes a cada permiso
        foreach ($permisos as &$permiso) {
            $permiso['adjuntos'] = $this->adjuntoModel
                ->where('perm_ide', $permiso['perm_ide'])
                ->where('padj_estado', 1)
                ->findAll();
        }

        return $permisos;
    }

    /**
     * Registra un nuevo permiso con sus archivos adjuntos.
     */
    public function registrarPermiso(array $datos, array $archivos): array
    {
        // 1. Validar que no exista cruce de horarios en la misma fecha
        if ($this->existeCruceHorario($datos['per_ide'], $datos['perm_fecha'], $datos['perm_hora_inicio'], $datos['perm_hora_fin'])) {
            return [
                'status'  => false,
                'message' => 'El personal ya registra un permiso o papeleta en el rango de horas ingresado.'
            ];
        }

        // 2. Calcular minutos totales acumulados
        $minutosTotales = $this->calcularDiferenciaMinutos($datos['perm_hora_inicio'], $datos['perm_hora_fin']);

        $dataInsert = [
            'per_ide'          => $datos['per_ide'],
            'tip_ide'          => $datos['tipo_permiso_id'],
            'perm_fecha'       => $datos['perm_fecha'],
            'perm_hora_inicio' => $datos['perm_hora_inicio'],
            'perm_hora_fin'    => $datos['perm_hora_fin'],
            'perm_minutos'     => $minutosTotales,
            'perm_numero_doc'  => $datos['perm_numero_doc'] ?? null,
            'perm_motivo'      => $datos['perm_motivo'] ?? null,
            'perm_estado'      => 1,
            'created_at'       => date('Y-m-d H:i:s')
        ];

        $this->db->transStart();

        // Insertar cabecera de permiso
        $permIde = $this->permisoModel->insert($dataInsert, true);

        // 3. Procesar archivos adjuntos si existen
        if (!empty($archivos['adjuntos'])) {
            $this->procesarAdjuntos($permIde, $archivos['adjuntos']);
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return [
                'status'  => false,
                'message' => 'Ocurrió un error al guardar la papeleta en la base de datos.'
            ];
        }

        return [
            'status'  => true,
            'message' => 'Papeleta de permiso registrada correctamente.',
            'perm_ide' => $permIde
        ];
    }

    /**
     * Procesa y mueve los archivos físicos al directorio de destino guardando la meta en la BD.
     */
    private function procesarAdjuntos(int $permIde, array $files): void
    {
        $uploadPath = WRITEPATH . 'uploads/permisos/' . date('Y/m/');

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        foreach ($files as $file) {
            if ($file->isValid() && !$file->hasMoved()) {
                $nombreOriginal = $file->getClientName();
                $nombreNuevo    = $file->getRandomName();

                $file->move($uploadPath, $nombreNuevo);

                $this->adjuntoModel->insert([
                    'perm_ide'          => $permIde,
                    'padj_nombre_orig'  => $nombreOriginal,
                    'padj_nombre_archivo' => 'uploads/permisos/' . date('Y/m/') . $nombreNuevo,
                    'padj_extension'    => $file->getClientExtension(),
                    'padj_tamano'       => $file->getSize(),
                    'padj_estado'       => 1,
                    'created_at'        => date('Y-m-d H:i:s')
                ]);
            }
        }
    }

    /**
     * Valida si existe un permiso activo en el mismo rango de horas.
     */
    private function existeCruceHorario(int $perIde, string $fecha, string $horaInicio, string $horaFin): bool
    {
        return $this->permisoModel
            ->where('per_ide', $perIde)
            ->where('perm_fecha', $fecha)
            ->where('perm_estado', 1)
            ->groupStart()
                ->where("('$horaInicio' BETWEEN perm_hora_inicio AND perm_hora_fin)")
                ->orWhere("('$horaFin' BETWEEN perm_hora_inicio AND perm_hora_fin)")
                ->orWhere("(perm_hora_inicio BETWEEN '$horaInicio' AND '$horaFin')")
            ->groupEnd()
            ->countAllResults() > 0;
    }

    /**
     * Calcula los minutos netos entre dos horas HH:MM.
     */
    private function calcularDiferenciaMinutos(string $hInicio, string $hFin): int
    {
        $inicio = new \DateTime($hInicio);
        $fin    = new \DateTime($hFin);
        $diff   = $inicio->diff($fin);

        return ($diff->h * 60) + $diff->i;
    }
}