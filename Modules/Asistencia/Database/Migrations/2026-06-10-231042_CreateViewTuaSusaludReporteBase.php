<?php

namespace Modules\Asistencia\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateViewTuaSusaludReporteBase extends Migration
{
    public function up()
    {
        $sql = "
        CREATE VIEW vw_tuasalud_reporte_base AS
        SELECT

            est.est_ipress                         AS codigo_unico,
            est.est_nombre                   AS establecimiento,
            red.red_nombre                         AS red,

            tdi.tdi_abreviatura                    AS tipo_documento,
            per.per_numero_documento,

            car.car_nombre                         AS cargo,
            perl.perl_numero_colegiatura           AS numero_colegiatura,

            CONCAT(
                per.per_paterno,
                ' ',
                per.per_materno
            )                                      AS apellidos,

            per.per_nombre                         AS nombres,

            esp.se_nombre                          AS especialidad,

            uss.uss_nombre                         AS servicio,

            prog.prog_fecha,

            th.th_hora_ingreso,
            th.th_hora_salida,

            perl.perl_ide

        FROM casis_personal perl

            INNER JOIN casis_persona per
                ON per.per_ide = perl.perl_per_ide

            LEFT JOIN casis_tipo_documento_identidad tdi
                ON tdi.tdi_ide = per.per_tdi_ide

            LEFT JOIN casis_cargo car
                ON car.car_ide = perl.perl_car_ide

            LEFT JOIN casis_segunda_especialidad esp
                ON esp.se_ide = perl.perl_se_ide

            LEFT JOIN casis_establecimiento est
                ON est.est_ide = perl.perl_est_ide

            LEFT JOIN casis_microred mic
                ON mic.mic_ide = est.est_mic_ide

            LEFT JOIN casis_red red
                ON red.red_ide = mic.mic_red_ide

            LEFT JOIN casis_programacion prog
                ON prog.prog_perl_ide = perl.perl_ide

            LEFT JOIN casis_turno tur
                ON tur.tur_ide = prog.prog_th_ide

            LEFT JOIN casis_turno_horario th
                ON th.th_tur_ide = tur.tur_ide

            LEFT JOIN casis_establecimiento_upss_servicio eus
                ON eus.eus_ide = prog.prog_eus_ide

            LEFT JOIN casis_upss_servicio uss
                ON uss.uss_ide = eus.eus_uss_ide
        ";

        $this->db->query($sql);
    }

    public function down()
    {
        $this->db->query(
            "DROP VIEW IF EXISTS vw_tuasalud_reporte_base"
        );
    }
}
