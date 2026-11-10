<?php

namespace Modules\Asistencia\Services;

use Config\Database;
use CodeIgniter\Database\BaseBuilder;

/** Centraliza los perfiles, capacidades y el ámbito territorial de Asistencia. */
class AccesoAsistenciaService
{
    private const GLOBALES_COMPLETOS = ['superadmin', 'asistencia', 'asi_sua', 'asi_adm', 'asi_dm'];
    private const NIVELES = [
        'asi_diresa' => 'diresa', 'asi_diresa_apoyo' => 'diresa', 'asi_diresa_registro' => 'diresa', 'asi_diresa_consulta' => 'diresa',
        'asi_red' => 'red', 'asi_red_apoyo' => 'red', 'asi_red_registro' => 'red', 'asi_red_consulta' => 'red',
        'asi_mred' => 'microred', 'asi_mred_apoyo' => 'microred', 'asi_mred_registro' => 'microred', 'asi_mred_consulta' => 'microred',
        'asi_est' => 'establecimiento', 'asi_est_apoyo' => 'establecimiento', 'asi_est_registro' => 'establecimiento', 'asi_est_consulta' => 'establecimiento',
    ];

    private array $contextos = [];

    public function grupos(): array
    {
        if (!function_exists('auth') || !auth()->loggedIn()) return [];
        $user = auth()->user();
        $groups = [];
        foreach (array_keys(config('AuthGroups')->groups) as $group) {
            if ($user->inGroup($group)) $groups[] = $group;
        }
        return $groups;
    }

    public function esSuperadministrador(): bool
    {
        return (bool) array_intersect(self::GLOBALES_COMPLETOS, $this->grupos());
    }

    public function perfilTerritorial(): ?array
    {
        if ($this->esSuperadministrador()) return ['global' => true, 'nivel' => null, 'grupo' => null, 'modo' => 'completo'];
        $groups = $this->grupos();
        foreach (self::NIVELES as $group => $nivel) {
            if (!in_array($group, $groups, true)) continue;
            $modo = str_ends_with($group, '_consulta') ? 'consulta'
                : (str_ends_with($group, '_registro') ? 'registro'
                    : (str_ends_with($group, '_apoyo') ? 'apoyo' : 'completo'));
            return ['global' => false, 'nivel' => $nivel, 'grupo' => $group, 'modo' => $modo] + $this->resolverContexto($nivel);
        }
        if (array_intersect(['asi_apo', 'asi_apoyo'], $groups)) return ['global' => true, 'nivel' => null, 'grupo' => 'asi_apoyo', 'modo' => 'apoyo'];
        if (in_array('asi_auditoria', $groups, true)) return ['global' => true, 'nivel' => null, 'grupo' => 'asi_auditoria', 'modo' => 'auditoria'];
        return null;
    }

    public function permitir(string $capacidad): bool
    {
        $perfil = $this->perfilTerritorial();
        if (!$perfil) return false;
        if ($perfil['modo'] === 'auditoria') return in_array($capacidad, ['lectura_auditoria', 'lectura_reportes'], true);
        if ($perfil['modo'] === 'completo' && $perfil['global']) return true;
        if ($capacidad === 'lectura' || $capacidad === 'lectura_personal' || $capacidad === 'lectura_reportes') return true;
        if ($capacidad === 'lectura_periodos') return in_array($perfil['modo'], ['completo', 'apoyo', 'consulta'], true);
        if ($perfil['modo'] === 'consulta') return in_array($capacidad, ['lectura_personal', 'lectura_reportes', 'lectura_periodos', 'lectura_catalogos'], true);
        if ($capacidad === 'crear_personal') return in_array($perfil['modo'], ['completo', 'apoyo', 'registro'], true);
        if (in_array($capacidad, ['editar_personal', 'eliminar_personal'], true)) return in_array($perfil['modo'], ['completo', 'apoyo'], true);
        if ($capacidad === 'crear_operacion') return in_array($perfil['modo'], ['completo', 'apoyo', 'registro'], true);
        if (in_array($capacidad, ['editar_operacion', 'eliminar_operacion'], true)) return in_array($perfil['modo'], ['completo', 'apoyo'], true);
        if ($capacidad === 'lectura_catalogos') return in_array($perfil['modo'], ['completo', 'consulta'], true);
        if ($capacidad === 'gestionar_periodos' || $capacidad === 'gestionar_grupos_corte') return $perfil['modo'] === 'completo';
        if ($capacidad === 'gestionar_catalogos') return $perfil['modo'] === 'completo';
        if ($capacidad === 'lectura_personal' || $capacidad === 'lectura_reportes' || $capacidad === 'lectura_periodos') return true;
        return false;
    }

    public function exigir(string $capacidad): void
    {
        if (!$this->permitir($capacidad)) throw new \DomainException('No tiene permisos para esta operación de Asistencia.');
    }

    public function aplicarAmbitoPersonal(BaseBuilder $builder, string $personalAlias = 'pl', string $establecimientoCol = 'perl_est_ide'): void
    {
        $perfil = $this->perfilTerritorial();
        if (!$perfil) { $builder->where('1 =', 0); return; }
        if ($perfil['global'] ?? false) return;
        $ids = $this->establecimientosDelAmbito();
        if (!$ids) { $builder->where('1 =', 0); return; }
        $builder->whereIn($personalAlias . '.' . $establecimientoCol, $ids);
    }

    public function personalDentroAmbito(int $personalId): bool
    {
        $perfil = $this->perfilTerritorial();
        if (!$perfil) return false;
        if ($perfil['global'] ?? false) return true;
        $db = Database::connect();
        $ids = $this->establecimientosDelAmbito();
        if (!$ids) return false;
        $row = $db->table('casis_personal p')->select('p.perl_ide')
            ->where('p.perl_ide', $personalId)->where('p.deleted_at', null)
            ->whereIn('p.perl_est_ide', $ids)->get()->getRowArray();
        return (bool) $row;
    }

    public function personaDentroAmbito(int $personaId): bool
    {
        $perfil = $this->perfilTerritorial();
        if (!$perfil) return false;
        if ($perfil['global'] ?? false) return true;
        $db = Database::connect();
        $ids = $this->establecimientosDelAmbito();
        if (!$ids) return false;
        return (bool) $db->table('casis_personal p')->join('casis_persona pe', 'pe.per_ide = p.perl_per_ide', 'inner')
            ->where('pe.per_ide', $personaId)->where('p.deleted_at', null)->where('pe.deleted_at', null)
            ->whereIn('p.perl_est_ide', $ids)->countAllResults();
    }

    public function establecimientoDentroAmbito(int $establecimientoId): bool
    {
        $perfil = $this->perfilTerritorial();
        if (!$perfil) return false;
        if ($perfil['global'] ?? false) return true;
        return in_array($establecimientoId, $this->establecimientosDelAmbito(), true);
    }

    public function microredDentroAmbito(int $microredId): bool
    {
        $perfil = $this->perfilTerritorial();
        if (!$perfil) return false;
        if ($perfil['global'] ?? false) return true;
        $ids = $this->establecimientosDelAmbito();
        if (!$ids) return false;
        return (bool) Database::connect()->table('casis_establecimiento')->where('est_mic_ide', $microredId)->whereIn('est_ide', $ids)->countAllResults();
    }

    public function puedeCrearEstablecimientoEnMicrored(int $microredId): bool
    {
        $perfil = $this->perfilTerritorial();
        if (!$perfil) return false;
        if ($perfil['global'] ?? false) return true;
        return $perfil['nivel'] !== 'establecimiento' && $this->microredDentroAmbito($microredId);
    }

    public function establecimientosDelAmbito(): array
    {
        $perfil = $this->perfilTerritorial();
        if (!$perfil || ($perfil['global'] ?? false)) return [];
        $ctx = $this->resolverContexto($perfil['nivel']);
        if (empty($ctx['entidad_id'])) return [];
        $db = Database::connect();
        $q = $db->table('casis_establecimiento e')->select('e.est_ide')->where('e.deleted_at', null);
        if ($perfil['nivel'] === 'establecimiento') $q->where('e.est_ide', $ctx['entidad_id']);
        else {
            $q->join('casis_microred m', 'm.mic_ide = e.est_mic_ide AND m.deleted_at IS NULL', 'inner')
                ->join('casis_red r', 'r.red_ide = m.mic_red_ide AND r.deleted_at IS NULL', 'inner');
            if ($perfil['nivel'] === 'microred') $q->where('m.mic_ide', $ctx['entidad_id']);
            if ($perfil['nivel'] === 'red') $q->where('r.red_ide', $ctx['entidad_id']);
            if ($perfil['nivel'] === 'diresa') $q->where('r.red_dir_ide', $ctx['entidad_id']);
        }
        return array_map('intval', array_column($q->get()->getResultArray(), 'est_ide'));
    }

    public function oficinaActualDeUsuario(): ?int
    {
        if (!function_exists('auth') || !auth()->loggedIn()) return null;
        $dni = trim((string)(auth()->user()->dni ?? ''));
        if ($dni === '') return null;
        $rows = Database::connect()->table('casis_personal p')->select('p.perl_ofi_ide')
            ->join('casis_persona pe', 'pe.per_ide = p.perl_per_ide AND pe.deleted_at IS NULL')
            ->join('casis_oficina o', 'o.ofi_ide = p.perl_ofi_ide AND o.deleted_at IS NULL')
            ->where('pe.per_numero_documento', $dni)->where('p.perl_estado', 1)->where('p.deleted_at', null)
            ->get()->getResultArray();
        $ids = array_values(array_unique(array_map('intval', array_column($rows, 'perl_ofi_ide'))));
        return count($ids) === 1 ? $ids[0] : null;
    }

    public function grupoCorteDentroAmbito(int $grupoId): bool
    {
        $perfil = $this->perfilTerritorial();
        if (!$perfil) return false;
        if ($perfil['global'] ?? false) return true;
        $db = Database::connect();
        $g = $db->table('casis_grupos_corte')->where('gco_ide', $grupoId)->where('deleted_at', null)->get()->getRowArray();
        if (!$g) return false;
        return $this->validarUbicacion($g);
    }

    public function gruposCorteDentroAmbito(array $grupos): array
    {
        $perfil = $this->perfilTerritorial();
        if (!$perfil) return [];
        if ($perfil['global'] ?? false) return $grupos;
        return array_values(array_filter($grupos, fn(array $g) => $this->validarUbicacion($g)));
    }

    public function validarUbicacion(array $datos): bool
    {
        $perfil = $this->perfilTerritorial();
        if (!$perfil) return false;
        if ($perfil['global'] ?? false) return true;
        $scopeIds = $this->establecimientosDelAmbito();
        if (!$scopeIds) return false;
        if (!empty($datos['establecimiento_id'])) return in_array((int)$datos['establecimiento_id'], $scopeIds, true);
        if ($perfil['nivel'] === 'establecimiento') return false;
        $db = Database::connect();
        if (!empty($datos['microred_id'])) {
            return (bool) $db->table('casis_establecimiento')->where('est_mic_ide', (int)$datos['microred_id'])->whereIn('est_ide', $scopeIds)->countAllResults();
        }
        if (!empty($datos['red_id'])) {
            return (bool) $db->table('casis_establecimiento e')->join('casis_microred m', 'm.mic_ide=e.est_mic_ide')->where('m.mic_red_ide', (int)$datos['red_id'])->whereIn('e.est_ide', $scopeIds)->countAllResults();
        }
        if (!empty($datos['diresa_id'])) {
            return (bool) $db->table('casis_establecimiento e')->join('casis_microred m', 'm.mic_ide=e.est_mic_ide')->join('casis_red r', 'r.red_ide=m.mic_red_ide')->where('r.red_dir_ide', (int)$datos['diresa_id'])->whereIn('e.est_ide', $scopeIds)->countAllResults();
        }
        return false;
    }

    private function resolverContexto(string $nivel): array
    {
        if (isset($this->contextos[$nivel])) return $this->contextos[$nivel];
        $user = auth()->user();
        $dni = trim((string)($user->dni ?? ''));
        if ($dni === '') return $this->contextos[$nivel] = ['entidad_id' => null, 'ambiguo' => false];
        $db = Database::connect();
        $rows = $db->table('casis_personal p')->select('e.est_ide, m.mic_ide, r.red_ide, r.red_dir_ide')
            ->join('casis_persona pe', 'pe.per_ide = p.perl_per_ide AND pe.deleted_at IS NULL', 'inner')
            ->join('casis_establecimiento e', 'e.est_ide = p.perl_est_ide AND e.deleted_at IS NULL', 'inner')
            ->join('casis_microred m', 'm.mic_ide = e.est_mic_ide AND m.deleted_at IS NULL', 'left')
            ->join('casis_red r', 'r.red_ide = m.mic_red_ide AND r.deleted_at IS NULL', 'left')
            ->where('pe.per_numero_documento', $dni)->where('p.deleted_at', null)->where('p.perl_estado', 1)
            ->get()->getResultArray();
        $field = ['diresa'=>'red_dir_ide','red'=>'red_ide','microred'=>'mic_ide','establecimiento'=>'est_ide'][$nivel];
        $ids = array_values(array_unique(array_filter(array_map(static fn($row) => (int)($row[$field] ?? 0), $rows))));
        return $this->contextos[$nivel] = ['entidad_id' => count($ids) === 1 ? $ids[0] : null, 'ambiguo' => count($ids) > 1];
    }
}
