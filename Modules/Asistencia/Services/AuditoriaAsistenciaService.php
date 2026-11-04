<?php

namespace Modules\Asistencia\Services;

use Config\Database;

class AuditoriaAsistenciaService
{
    public function resumen(): array
    {
        $db = Database::connect();
        $limit = 200;
        $perfiles = $db->tableExists('casis_acceso_auditoria')
            ? $db->table('casis_acceso_auditoria a')->select('a.id, a.accion, a.entidad_tipo, a.entidad_id, a.detalle, a.ip_address, a.created_at, u.username')
                ->join('users u', 'u.id=a.actor_id', 'left')->orderBy('a.created_at', 'DESC')->limit($limit)->get()->getResultArray()
            : [];
        return [
            'accesos' => $db->table('auth_logins l')->select('l.id, l.identifier, l.ip_address, l.user_agent, l.date, l.success, u.username')
                ->join('users u', 'u.id=l.user_id', 'left')->orderBy('l.date', 'DESC')->limit($limit)->get()->getResultArray(),
            'perfiles' => $perfiles,
            'periodos' => $db->table('casis_periodos_log l')->select('l.log_ide, l.per_ide, l.estado_anterior, l.estado_nuevo, l.log_accion, l.log_motivo, l.log_ip_address, l.created_at, u.username, p.per_nombre')
                ->join('users u', 'u.id=l.created_by', 'left')->join('casis_periodos p', 'p.per_ide=l.per_ide', 'left')->orderBy('l.created_at', 'DESC')->limit($limit)->get()->getResultArray(),
            'personal' => $db->table('casis_personal_historial h')->select('h.hist_perl_ide, h.perl_ide, h.perl_codigo, h.perl_est_ide, h.hist_accion, h.hist_hecho_por, h.hist_creado_en, u.username')
                ->join('users u', 'u.id=h.hist_hecho_por', 'left')->orderBy('h.hist_creado_en', 'DESC')->limit($limit)->get()->getResultArray(),
            'marcaciones' => $db->table('casis_asistencia a')->select('a.asi_ide, a.asi_perl_ide, a.asi_numero_documento, a.asi_fecha_hora, a.asi_origen, a.asi_tipo, a.asi_motivo, a.asi_ip_log, a.created_at, u.username')
                ->join('users u', 'u.id=a.asi_user_ide', 'left')->where('a.asi_origen', 'MANUAL')->orderBy('a.created_at', 'DESC')->limit($limit)->get()->getResultArray(),
            'roles' => $db->table('casis_plan_evento e')->select('e.id, e.rol_id, e.accion, e.detalle, e.created_at, u.username')
                ->join('users u', 'u.id=e.created_by', 'left')->orderBy('e.created_at', 'DESC')->limit($limit)->get()->getResultArray(),
            'calificaciones' => $db->table('casis_cal_evento e')->select('e.id, e.calificacion_id, e.accion, e.detalle, e.created_at, u.username')
                ->join('users u', 'u.id=e.created_by', 'left')->orderBy('e.created_at', 'DESC')->limit($limit)->get()->getResultArray(),
        ];
    }
}
