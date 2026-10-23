<?php

namespace Modules\Asistencia\Services;

/** Motor puro. Minutos enteros; no calcula descuentos ni remuneraciones. */
class CalificacionMotor
{
    public const DEFAULTS = ['tolerancia_minutos'=>0, 'tardanzas_excesivas'=>3, 'ventana_minutos'=>120, 'codigos_guardia'=>'GD,GN'];

    public static function evaluar(array $fuentes, array $reglas): array
    {
        $resultado = [];
        foreach ($fuentes['personal'] as $p) {
            $id = $p['perl_ide'];
            $r = ['personal'=>$p, 'minutos_programados'=>0, 'minutos_asistidos'=>0, 'minutos_permiso'=>0, 'minutos_tardanza'=>0, 'tardanzas'=>0, 'faltas'=>0, 'dias_licencia'=>0, 'dias_vacacion'=>0, 'guardias'=>0, 'guardias_domingo'=>0, 'guardias_feriado'=>0, 'observaciones'=>[], 'detalle'=>[]];
            $obs = static function ($codigo, $fecha = '') use (&$r) { $r['observaciones'][] = ['codigo'=>$codigo, 'fecha'=>$fecha]; };
            $turnos = array_values(array_filter($fuentes['turnos'], static fn($t)=>(int)$t['prog_perl_ide']===(int)$id));
            $marcas = array_values(array_filter($fuentes['marcas'], static fn($m)=>(int)$m['asi_perl_ide']===(int)$id));
            usort($turnos, static fn($a,$b)=>strcmp($a['prog_fecha'].' '.$a['th_hora_ingreso'], $b['prog_fecha'].' '.$b['th_hora_ingreso']));
            usort($marcas, static fn($a,$b)=>strcmp($a['asi_fecha_hora'],$b['asi_fecha_hora']));
            $usadas = []; $lic = []; $vac = [];
            foreach (['licencias'=>['rl', &$lic], 'vacaciones'=>['rv', &$vac]] as $tipo=>&$config) {
                [$pref] = $config;
                foreach ($fuentes[$tipo] as $l) {
                    if ((int)$l[$pref.'_perl_ide']!==(int)$id) continue;
                    for ($d=max($fuentes['inicio'], $l[$pref.'_fecha_inicio']); $d<=min($fuentes['fin'],$l[$pref.'_fecha_fin']); $d=date('Y-m-d',strtotime($d.' +1 day'))) $config[1][$d]=true;
                }
            }
            unset($config);
            $r['dias_licencia']=count($lic); $r['dias_vacacion']=count($vac);
            if (!$marcas && !$lic && !$vac) $obs('SIN_ASISTENCIA_NI_JUSTIFICACION');
            if (!$turnos) $obs('SIN_ROL');
            $anteriorFin = 0;
            foreach ($turnos as $t) {
                $d=$t['prog_fecha']; $a=strtotime($d.' '.$t['th_hora_ingreso']); $b=strtotime($d.' '.$t['th_hora_salida']);
                if ((!empty($p['perl_fecha_inicio']) && $d<$p['perl_fecha_inicio']) || (!empty($p['perl_fecha_cese']) && $d>$p['perl_fecha_cese'])) { $obs('TURNO_FUERA_DE_VINCULO',$d); continue; }
                if (!$t['th_hora_ingreso'] || !$t['th_hora_salida'] || $a===$b) { $obs('HORARIO_INVALIDO',$d); continue; }
                if ($b<$a) $b+=86400;
                if ($a<$anteriorFin) $obs('TURNOS_SUPERPUESTOS',$d);
                $anteriorFin=max($anteriorFin,$b);
                $r['minutos_programados']+=(int)(($b-$a)/60);
                if (in_array($t['tur_codigo'], array_map('trim',explode(',',$reglas['codigos_guardia'])),true)) {
                    $r['guardias']++; $r['guardias_domingo']+=(int)(date('w',$a)==0);
                    foreach ($fuentes['feriados'] as $f) if ($f['df_fecha']===$d || (!empty($f['df_repetitivo']) && substr($f['df_fecha'],5)===substr($d,5))) { $r['guardias_feriado']++; break; }
                }
                $candidatas=[];
                $ambigua=false;
                foreach ($marcas as $k=>$m) {
                    $ts=strtotime($m['asi_fecha_hora']);
                    if ($ts >= $a-60*$reglas['ventana_minutos'] && $ts <= $b+60*$reglas['ventana_minutos']) {
                        $coincidencias=0;
                        foreach ($turnos as $otro) {
                            $oa=strtotime($otro['prog_fecha'].' '.$otro['th_hora_ingreso']); $ob=strtotime($otro['prog_fecha'].' '.$otro['th_hora_salida']); if ($ob<$oa) $ob+=86400;
                            if ($ts >= $oa-60*$reglas['ventana_minutos'] && $ts <= $ob+60*$reglas['ventana_minutos']) $coincidencias++;
                        }
                        if ($coincidencias>1) $ambigua=true;
                        if (!isset($usadas[$k])) $candidatas[$k]=$m;
                    }
                }
                foreach ($candidatas as $k=>$m) $usadas[$k]=true;
                $permisos=[];
                foreach ($fuentes['permisos'] as $permiso) {
                    if ((int)$permiso['rp_perl_ide']!==(int)$id || $permiso['rp_estado']!=='APROBADO') continue;
                    if (empty($permiso['rp_hora_salida']) || empty($permiso['rp_hora_retorno'])) { $obs('PAPELETA_SIN_HORAS',$permiso['rp_fecha']); continue; }
                    $pa=strtotime($permiso['rp_fecha'].' '.$permiso['rp_hora_salida']); $pb=strtotime($permiso['rp_fecha'].' '.$permiso['rp_hora_retorno']);
                    if ($pb<$pa) $pb+=86400;
                    if ($pb>$a && $pa<$b) $permisos[]=[max($a,$pa),min($b,$pb)];
                }
                $permisoMin=self::minutosUnion($permisos);
                $r['minutos_permiso']+=$permisoMin;
                $justificado=isset($lic[$d]) || isset($vac[$d]) || $permisoMin>=($b-$a)/60;
                $min=0; $tarde=0;
                if ($ambigua) $obs('MARCACION_COMPARTIDA_ENTRE_TURNOS',$d);
                elseif ($justificado) { if ($candidatas && (isset($lic[$d]) || isset($vac[$d]))) $obs('ASISTENCIA_CON_LICENCIA_O_VACACION',$d); }
                elseif (!$candidatas) { $r['faltas']++; $obs('FALTA_SIN_LICENCIA',$d); }
                elseif (count($candidatas)%2!==0) $obs('MARCACIONES_INCOMPLETAS_O_MULTIPLES',$d);
                else {
                    $par=array_values($candidatas); $entrada=strtotime($par[0]['asi_fecha_hora']); $salida=strtotime($par[count($par)-1]['asi_fecha_hora']);
                    $valida=true; $intervalos=[];
                    for ($j=0;$j<count($par);$j+=2) {
                        $ea=strtotime($par[$j]['asi_fecha_hora']); $eb=strtotime($par[$j+1]['asi_fecha_hora']);
                        if ($par[$j]['asi_tipo']!=='ENTRADA' || $par[$j+1]['asi_tipo']!=='SALIDA' || $eb<=$ea) $valida=false;
                        if (min($b,$eb)>max($a,$ea)) $intervalos[]=[max($a,$ea),min($b,$eb)];
                    }
                    if (!$valida) $obs('SECUENCIA_MARCACIONES_INVALIDA',$d);
                    else {
                        $intersecciones=[]; $permisoIngreso=[];
                        foreach ($permisos as [$pa,$pb]) {
                            foreach ($intervalos as [$ia,$ib]) if (min($pb,$ib)>max($pa,$ia)) $intersecciones[]=[max($pa,$ia),min($pb,$ib)];
                            if (min($entrada,$pb)>max($a,$pa)) $permisoIngreso[]=[max($a,$pa),min($entrada,$pb)];
                        }
                        $min=max(0,self::minutosUnion($intervalos)-self::minutosUnion($intersecciones));
                        $tarde=max(0,(int)((min($b,$entrada)-$a)/60)-self::minutosUnion($permisoIngreso)-(int)$reglas['tolerancia_minutos']);
                        if ($tarde) { $r['tardanzas']++; $r['minutos_tardanza']+=$tarde; }
                        if ($salida<$b && self::minutosUnion(array_merge($intervalos,$permisos))<($b-$a)/60) $obs('SALIDA_ANTICIPADA',$d);
                        $r['minutos_asistidos']+=$min;
                    }
                }
                $r['detalle'][]=['fecha'=>$d,'turno'=>$t['tur_codigo'],'minutos_asistidos'=>$min,'minutos_tardanza'=>$tarde,'justificado'=>$justificado];
            }
            foreach ($marcas as $k=>$m) if (!isset($usadas[$k]) && substr($m['asi_fecha_hora'],0,10)>=$fuentes['inicio'] && substr($m['asi_fecha_hora'],0,10)<=$fuentes['fin']) $obs('ASISTENCIA_SIN_TURNO',substr($m['asi_fecha_hora'],0,10));
            foreach ($fuentes['permisos'] as $permiso) if ((int)$permiso['rp_perl_ide']===(int)$id && $permiso['rp_estado']!=='APROBADO') $obs('REVISAR_PAPELETA_'.$permiso['rp_estado'],$permiso['rp_fecha']);
            if ($r['tardanzas'] >= (int)$reglas['tardanzas_excesivas']) $obs('TARDANZAS_EXCESIVAS');
            $resultado[]=$r;
        }
        return $resultado;
    }

    private static function minutosUnion(array $intervalos): int
    {
        sort($intervalos); $segundos=0; $fin=0;
        foreach ($intervalos as [$a,$b]) { $segundos+=max(0,$b-max($a,$fin)); $fin=max($fin,$b); }
        return (int)floor($segundos/60);
    }
}
