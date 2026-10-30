<?php

namespace Modules\Asistencia\Services;

/** CRUD de delegaciones temporales por oficina y etapa del flujo de roles. */
class DelegacionRolService
{
    private $db;
    private GestionRolService $gestion;

    public function __construct($db = null, ?GestionRolService $gestion = null)
    {
        $this->db = $db ?? \Config\Database::connect();
        $this->gestion = $gestion ?? new GestionRolService();
    }

    public function listar(string $busqueda = '', string $etapa = ''): array
    {
        $this->gestion->exigirAdministrador();
        $q = $this->db->table('casis_plan_responsable r')
            ->select('r.*, o.ofi_nombre, u.username, u.nombres, u.paterno, u.materno')
            ->join('casis_oficina o', 'o.ofi_ide = r.oficina_id')
            ->join(config('Auth')->tables['users'] . ' u', 'u.id = r.usuario_id')
            ->orderBy('r.hasta', 'DESC')->orderBy('r.id', 'DESC');
        if ($etapa !== '' && in_array($etapa, ['PROGRAMACION', 'RRHH', 'DIRECCION'], true)) $q->where('r.etapa', $etapa);
        if ($busqueda !== '') {
            $q->groupStart()->like('o.ofi_nombre', $busqueda)->orLike('u.username', $busqueda)
                ->orLike('u.nombres', $busqueda)->orLike('u.paterno', $busqueda)->orLike('u.materno', $busqueda)->groupEnd();
        }
        return $q->get()->getResultArray();
    }

    public function guardar(array $datos): int
    {
        $this->gestion->exigirAdministrador();
        $valorId = $datos['id'] ?? '';
        $id = $valorId === '' || $valorId === '0' || $valorId === 0 ? 0 : $this->entero($valorId, 'delegación');
        $usuarioId = $this->entero($datos['usuario_id'] ?? '', 'usuario');
        $oficinaId = $this->entero($datos['oficina_id'] ?? '', 'oficina');
        $etapa = strtoupper(trim((string) ($datos['etapa'] ?? '')));
        if (!in_array($etapa, ['PROGRAMACION', 'RRHH', 'DIRECCION'], true)) throw new \InvalidArgumentException('Seleccione una etapa válida.');
        $desde = $this->fecha((string) ($datos['desde'] ?? ''));
        $hasta = $this->fecha((string) ($datos['hasta'] ?? ''));
        if ($desde > $hasta) throw new \InvalidArgumentException('La fecha Desde debe ser anterior o igual a Hasta.');
        $users = config('Auth')->tables['users'];
        if (!$this->db->table($users)->where('id', $usuarioId)->where('deleted_at', null)->where('active', 1)->countAllResults()) throw new \InvalidArgumentException('El usuario no existe o está desactivado.');
        if (!$this->db->table('casis_oficina')->where('ofi_ide', $oficinaId)->where('deleted_at', null)->where('ofi_estado', 1)->countAllResults()) throw new \InvalidArgumentException('La oficina no existe o está inactiva.');
        if ($id && !$this->db->table('casis_plan_responsable')->where('id', $id)->countAllResults()) throw new \OutOfBoundsException('La delegación que intenta editar ya no existe.');
        $duplicada = $this->db->table('casis_plan_responsable')->where('usuario_id', $usuarioId)->where('oficina_id', $oficinaId)
            ->where('etapa', $etapa)->where('desde <=', $hasta)->where('hasta >=', $desde);
        if ($id) $duplicada->where('id !=', $id);
        if ($duplicada->countAllResults()) throw new \DomainException('Ya existe una delegación del mismo usuario, oficina y etapa con fechas que se cruzan.');

        return $this->gestion->transaccion(function () use ($id, $usuarioId, $oficinaId, $etapa, $desde, $hasta): int {
            $data = [
                'usuario_id' => $usuarioId, 'oficina_id' => $oficinaId, 'desde' => $desde, 'hasta' => $hasta,
                'aprobar' => $etapa === 'PROGRAMACION' ? 0 : 1,
                'programar' => $etapa === 'PROGRAMACION' ? 1 : 0,
                'etapa' => $etapa,
            ];
            if ($id) {
                $this->db->table('casis_plan_responsable')->where('id', $id)->update($data);
                $accion = 'ACTUALIZAR_DELEGACION_ROL';
            } else {
                $data['created_by'] = $this->gestion->usuario();
                $data['created_at'] = $this->gestion->ahora();
                $this->db->table('casis_plan_responsable')->insert($data);
                $id = (int) $this->db->insertID();
                $accion = 'CREAR_DELEGACION_ROL';
            }
            $this->gestion->evento($accion, ['delegacion_id' => $id] + $data);
            return $id;
        });
    }

    public function eliminar(int $id): void
    {
        $this->gestion->exigirAdministrador();
        $row = $this->db->table('casis_plan_responsable')->where('id', $id)->get()->getRowArray();
        if (!$row) throw new \OutOfBoundsException('La delegación ya no existe.');
        $this->gestion->transaccion(function () use ($id, $row): void {
            $this->db->table('casis_plan_responsable')->where('id', $id)->delete();
            $this->gestion->evento('ELIMINAR_DELEGACION_ROL', ['delegacion_id' => $id] + $row);
        });
    }

    private function entero($valor, string $campo): int
    {
        if (!is_scalar($valor) || !ctype_digit((string) $valor) || (int) $valor < 1) throw new \InvalidArgumentException('Seleccione un ' . $campo . ' válido.');
        return (int) $valor;
    }

    private function fecha(string $valor): string
    {
        $fecha = \DateTimeImmutable::createFromFormat('!Y-m-d', $valor);
        if (!$fecha || $fecha->format('Y-m-d') !== $valor) throw new \InvalidArgumentException('Ingrese fechas válidas para la vigencia.');
        return $valor;
    }
}
