<?php

namespace Modules\Asistencia\Services;

class ConfiguracionRolService extends GestionRolService
{
    public static function normalizarReglas(string $json): array
    {
        try { $reglas = json_decode($json, true, 512, JSON_THROW_ON_ERROR); }
        catch (\JsonException $e) { throw new \DomainException('El formato de las reglas no es válido.'); }
        if (!is_array(json_decode($json)) || !is_array($reglas) || !array_is_list($reglas)) throw new \DomainException('Las reglas deben ser una lista.');
        $resultado = [];
        foreach ($reglas as $r) {
            if (!is_array($r) || !is_string($r['nombre'] ?? null) || trim($r['nombre']) === '' || mb_strlen($r['nombre']) > 150
                || !in_array($r['tipo'] ?? '', ['TURNOS_PERMITIDOS', 'MAX_TURNOS', 'MAX_HORAS_MES', 'MAX_CONTINUAS'], true)) throw new \DomainException('Complete el nombre y tipo de cada regla.');
            $regla = ['nombre' => trim($r['nombre']), 'tipo' => $r['tipo']];
            if ($r['tipo'] !== 'TURNOS_PERMITIDOS') {
                if (!is_numeric($r['limite'] ?? null) || !is_finite((float)$r['limite']) || $r['limite'] < 0) throw new \DomainException('El límite debe ser un número mayor o igual a cero.');
                if ($r['tipo'] === 'MAX_TURNOS' && floor((float)$r['limite']) !== (float)$r['limite']) throw new \DomainException('La cantidad de turnos debe ser entera.');
                $regla['limite'] = (float)$r['limite'];
            }
            if (in_array($r['tipo'], ['TURNOS_PERMITIDOS', 'MAX_TURNOS'], true)) {
                if (!is_array($r['turnos'] ?? null) || !array_is_list($r['turnos']) || !$r['turnos']) throw new \DomainException('Seleccione los turnos de la regla.');
                foreach ($r['turnos'] as $codigo) if (!is_string($codigo) || trim($codigo) === '') throw new \DomainException('Código de turno inválido.');
                $regla['turnos'] = array_values(array_unique(array_map('trim', $r['turnos'])));
            }
            foreach (['oficina_id', 'regimen_id'] as $campo) {
                if (($r[$campo] ?? '') === '') continue;
                if (!is_scalar($r[$campo]) || !ctype_digit((string)$r[$campo]) || (int)$r[$campo] < 1) throw new \DomainException('Ámbito de regla inválido.');
                $regla[$campo] = (int)$r[$campo];
            }
            if (!empty($r['modalidad'])) {
                if (!in_array($r['modalidad'], ['ASISTENCIAL', 'ADMINISTRATIVO'], true)) throw new \DomainException('Modalidad de regla inválida.');
                $regla['modalidad'] = $r['modalidad'];
            }
            $resultado[] = $regla;
        }
        return $resultado;
    }

    private function reglas(string $json, ?int $establecimiento = null): string
    {
        $reglas = self::normalizarReglas($json);
        foreach ($reglas as $r) {
            if (isset($r['oficina_id'])) {
                $q = $this->db->table('casis_oficina')->where('ofi_ide', $r['oficina_id'])->where('deleted_at', null);
                if ($establecimiento !== null) $q->where('ofi_est_ide', $establecimiento);
                if (!$q->countAllResults()) throw new \DomainException('La oficina de una regla no pertenece al establecimiento o ya no existe.');
            }
            if (isset($r['regimen_id']) && !$this->db->table('casis_modalidad_contrato')->where('mco_ide', $r['regimen_id'])->where('deleted_at', null)->countAllResults()) throw new \DomainException('Régimen contractual no encontrado.');
            foreach ($r['turnos'] ?? [] as $codigo) {
                if (!$this->db->table('casis_turno')->where('tur_codigo', $codigo)->where('deleted_at', null)->countAllResults()) throw new \DomainException('Turno no encontrado: ' . $codigo);
            }
        }
        return json_encode($reglas, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
    }

    public function guardarPlantilla(array $datos, int $id = 0): void
    {
        $this->exigirAdministrador();
        $nombre = trim((string)($datos['nombre'] ?? ''));
        if ($nombre === '' || mb_strlen($nombre) > 150) throw new \DomainException('Indique un nombre de hasta 150 caracteres.');
        $data = ['nombre' => $nombre, 'descripcion' => trim((string)($datos['descripcion'] ?? '')), 'reglas' => $this->reglas($datos['reglas'] ?? '[]')];
        $this->transaccion(function () use ($id, $data) {
            if ($id) {
                $anterior = $this->db->table('casis_plan_plantilla')->where('id', $id)->where('deleted_at', null)->get()->getRowArray();
                if (!$anterior) throw new \DomainException('Plantilla no encontrada.');
                $this->db->table('casis_plan_plantilla')->where('id', $id)->update($data + ['updated_at' => $this->ahora()]);
            } else {
                $this->db->table('casis_plan_plantilla')->insert($data + ['created_at' => $this->ahora(), 'created_by' => $this->usuario()]);
                $id = (int)$this->db->insertID();
            }
            $this->evento('GUARDAR_PLANTILLA_ROL', ['id' => $id, 'anterior' => $anterior ?? null, 'nuevo' => $data]);
        });
    }

    public function eliminarPlantilla(int $id): void
    {
        $this->exigirAdministrador();
        $this->transaccion(function () use ($id) {
            $anterior = $this->db->table('casis_plan_plantilla')->where('id', $id)->where('deleted_at', null)->get()->getRowArray();
            if (!$anterior) throw new \DomainException('Plantilla no encontrada.');
            $this->db->table('casis_plan_plantilla')->where('id', $id)->update(['deleted_at' => $this->ahora()]);
            $this->evento('ELIMINAR_PLANTILLA_ROL', $anterior);
        });
    }

    public function guardarPeriodo(array $datos, int $id = 0): void
    {
        $this->exigirAdministrador();
        $datos['reglas'] = $this->reglas($datos['reglas'] ?? '[]', (int)($datos['est_id'] ?? 0));
        $data = self::datosPeriodo($datos);
        if (!$this->db->table('casis_establecimiento')->where('est_ide', $data['est_id'])->where('deleted_at', null)->countAllResults()) throw new \DomainException('Establecimiento no encontrado.');
        $this->transaccion(function () use ($id, $data) {
            $anterior = $id ? $this->periodoModificable($id) : null;
            $q = $this->db->table('casis_plan_periodo')->where('est_id', $data['est_id'])->where('mes', $data['mes']);
            if ($id) $q->where('id !=', $id);
            if ($q->countAllResults()) throw new \DomainException('Ya existe un período para ese establecimiento y mes.');
            if ($id) $this->db->table('casis_plan_periodo')->where('id', $id)->update($data);
            else {
                $this->db->table('casis_plan_periodo')->insert($data + ['created_at' => $this->ahora(), 'created_by' => $this->usuario()]);
                $id = (int)$this->db->insertID();
            }
            $this->evento('GUARDAR_PERIODO', ['id' => $id, 'anterior' => $anterior, 'nuevo' => $data]);
        });
    }

    public static function datosPeriodo(array $d): array
    {
        if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $d['mes'] ?? '')) throw new \DomainException('Mes inválido.');
        $data = ['est_id' => (int)($d['est_id'] ?? 0), 'mes' => $d['mes'], 'reglas' => $d['reglas'] ?? '[]'];
        foreach (['apertura', 'aprobacion', 'cierre'] as $campo) {
            $valor = str_replace('T', ' ', $d[$campo] ?? '');
            if (strlen($valor) === 16) $valor .= ':00';
            $fecha = \DateTimeImmutable::createFromFormat('!Y-m-d H:i:s', $valor);
            if (!$fecha || $fecha->format('Y-m-d H:i:s') !== $valor) throw new \DomainException('Fecha inválida: ' . $campo);
            $data[$campo] = $valor;
        }
        if (!($data['apertura'] < $data['aprobacion'] && $data['aprobacion'] < $data['cierre'])) throw new \DomainException('Orden requerido: apertura < aprobación < cierre.');
        return $data;
    }

    private function periodoModificable(int $id): array
    {
        $sql = 'SELECT * FROM casis_plan_periodo WHERE id = ?' . ($this->db->DBDriver === 'SQLite3' ? '' : ' FOR UPDATE');
        $p = $this->db->query($sql, [$id])->getRowArray();
        if (!$p) throw new \DomainException('Período no encontrado.');
        if (self::etapa($p, $this->ahora()) !== 'PROGRAMADO') throw new \DomainException('Solo se puede editar o eliminar un período antes de su apertura.');
        if ($this->db->table('casis_plan_rol')->where('periodo_id', $id)->countAllResults()) throw new \DomainException('El período ya tiene roles asociados.');
        $inicio = $p['mes'] . '-01';
        $fin = (new \DateTimeImmutable($inicio))->modify('last day of this month')->format('Y-m-d');
        if ($this->db->table('casis_programacion pr')->join('casis_personal pe', 'pe.perl_ide = pr.prog_perl_ide')->where('pe.perl_est_ide', $p['est_id'])->where('pr.prog_fecha >=', $inicio)->where('pr.prog_fecha <=', $fin)->where('pr.deleted_at', null)->countAllResults()) throw new \DomainException('El período ya tiene turnos programados.');
        return $p;
    }

    public function eliminarPeriodo(int $id): void
    {
        $this->exigirAdministrador();
        $this->transaccion(function () use ($id) {
            $anterior = $this->periodoModificable($id);
            $this->db->table('casis_plan_periodo')->where('id', $id)->delete();
            $this->evento('ELIMINAR_PERIODO', $anterior);
        });
    }
}
