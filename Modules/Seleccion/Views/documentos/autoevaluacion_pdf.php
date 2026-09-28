<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ficha de Autoevaluación - Profesional</title>
    <style>
        @page {
            margin: 12mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #000;
            line-height: 1.2;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        .mb-1 {
            margin-bottom: 4px;
        }

        .mb-2 {
            margin-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 3px 5px;
            vertical-align: middle;
        }

        .bg-light {
            background-color: #f2f2f2;
        }

        .header-title {
            text-align: center;
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 8px;
        }

        .notes {
            font-size: 9px;
            margin-bottom: 8px;
        }

        .notes ul {
            margin: 0;
            padding-left: 15px;
        }

        .signature-box {
            margin-top: 40px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>

<?php
    if($postulante['grupo_ocupacional'] == 1):?>
<body>

    <div class="header-title">
         <?= $convocatoria['numero']??'CONVOCATORIA'?><br><br>
        FICHA DE AUTO EVALUACIÓN CURRICULAR<br>
        PROFESIONALES
    </div>

    <!-- DATOS DEL POSTULANTE -->
    <table>
        <tr>
            <td width="30%" class="fw-bold">Nombres y Apellidos:</td>
            <td><?= esc($postulante['nombres_completos'] ?? '') ?></td>
        </tr>
        <tr>
            <td class="fw-bold">N° DNI:</td>
            <td><?= esc($postulante['documento'] ?? '') ?></td>
        </tr>
        <tr>
            <td class="fw-bold">Cargo al que postula:</td>
            <td><?= esc($postulante['cargo'] ?? '') ?></td>
        </tr>
        <tr>
            <td class="fw-bold">Código de plaza AIRHSP:</td>
            <td><?= esc($postulante['codigo_airhsp'] ?? '-') ?></td>
        </tr>
    </table>

    <!-- 1. EVALUACION CURRICULAR -->
    <div class="fw-bold mb-1">1.- EVALUACION CURRICULAR</div>
    <table>
        <thead>
            <tr class="bg-light text-center fw-bold">
                <th>RUBRO</th>
                <th width="18%">PUNTAJE MÁXIMO</th>
                <th width="18%">AUTO EVALUACIÓN</th>
                <th width="18%">REVISIÓN</th>
            </tr>
        </thead>
        <tbody>
            <tr class="fw-bold bg-light">
                <td>A. Títulos y grados</td>
                <td class="text-center">55 puntos</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>a. Título profesional</td>
                <td class="text-center">30 puntos</td>
                <td class="text-center"><?= $profesion['titulo_profesional_auto'] ?? '' ?></td>
                <td class="text-center"><?= $profesion['titulo_profesional_rev'] ?? '' ?></td>
            </tr>
            <tr>
                <td>b. Título de especialidad (si corresponde)</td>
                <td class="text-center">10 puntos</td>
                <td class="text-center"><?= $profesion['titulo_especialidad_auto'] ?? '' ?></td>
                <td class="text-center"><?= $profesion['titulo_especialidad_rev'] ?? '' ?></td>
            </tr>
            <tr>
                <td>c. Grado de doctorado</td>
                <td class="text-center">10 puntos</td>
                <td class="text-center"><?= $profesion['doctorado_auto'] ?? '' ?></td>
                <td class="text-center"><?= $profesion['doctorado_rev'] ?? '' ?></td>
            </tr>
            <tr>
                <td>d. Grado de maestría</td>
                <td class="text-center">05 puntos</td>
                <td class="text-center"><?= $profesion['maestria_auto'] ?? '' ?></td>
                <td class="text-center"><?= $profesion['maestria_rev'] ?? '' ?></td>
            </tr>
            <tr class="fw-bold bg-light">
                <td>B. Diplomados, eventos y cursos de capacitación</td>
                <td class="text-center">20 puntos</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>a. Cursos iguales o mayores a 500 hrs. lectivas, 02 puntos por cada certificado máximo 5 certificados</td>
                <td class="text-center">10 puntos</td>
                <td class="text-center"><?= $capacitaciones['cursos_mayores_auto'] ?? '' ?></td>
                <td class="text-center"><?= $capacitaciones['cursos_mayores_rev'] ?? '' ?></td>
            </tr>
            <tr>
                <td>b. Cursos menores a 500 hrs. lectivas, 0.02 punto por cada hora lectiva se calificarán máximo 500 horas acumuladas.</td>
                <td class="text-center">10 puntos</td>
                <td class="text-center"><?= $capacitaciones['cursos_menores_auto'] ?? '' ?></td>
                <td class="text-center"><?= $capacitaciones['cursos_menores_rev'] ?? '' ?></td>
            </tr>
            <tr class="fw-bold bg-light">
                <td>C. Identificación institucional</td>
                <td class="text-center">5 puntos</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>a. Resolución de Encargo o Designación 0.5 punto por cada resolución (máximo 5)</td>
                <td class="text-center">2.5 puntos</td>
                <td class="text-center"><?= $identificacion['res_encargo_auto'] ?? '' ?></td>
                <td class="text-center"><?= $identificacion['res_encargo_rev'] ?? '' ?></td>
            </tr>
            <tr>
                <td>b. Resolución de Felicitación 0.5 punto por cada resolución (máximo 5)</td>
                <td class="text-center">2.5 puntos</td>
                <td class="text-center"><?= $identificacion['res_felicitacion_auto'] ?? '' ?></td>
                <td class="text-center"><?= $identificacion['res_felicitacion_rev'] ?? '' ?></td>
            </tr>
            <tr class="fw-bold bg-light">
                <td>D. Experiencia laboral</td>
                <td class="text-center">20 puntos</td>
                <td class="text-center"></td>
                <td class="text-center"></td>

            </tr>
            <tr>
                <td>a. Experiencia Laboral General* 04 puntos por año (Máximo 5 años)</td>
                <td class="text-center">20 puntos</td>
                <td class="text-center"><?= $experiencia['exp_general_auto'] ?? '' ?></td>
                <td class="text-center"><?= $experiencia['exp_general_rev'] ?? '' ?></td>
            </tr>
            <tr class="fw-bold bg-light">
                <td class="text-end">TOTAL</td>
                <td class="text-center">100 puntos</td>
                <td class="text-center"><?= $total['total_auto'] ?? '' ?></td>
                <td class="text-center"><?= $total['total_rev'] ?? '' ?></td>
            </tr>
        </tbody>
    </table>

    <!-- NOTAS APOSTILLADAS -->
    <div class="notes">
        <ul>
            <li>Solo se calificarán los certificados con una antigüedad no mayor de 5 años y posteriores a la expedición del Título Profesional y/o Técnico.</li>
            <li>En los cursos que no especifiquen horas se tomará la equivalencia de 17 horas por cada crédito.</li>
        </ul>
        <br>
        <strong>B. EXPERIENCIA LABORAL (Máximo 20 puntos)</strong>
        <ul>
            <li>Experiencia laboral General: Contratos con el Ministerio de Salud – MINSA y sus órganos desconcentrados, se calificará los contratos bajo el D.L. N° 276 con Resoluciones y Contratos bajo el D.L. 1057 (CAS) y D.L. N° 728 (Régimen privado). No se calificarán los contratos por locación de servicios, ni constancias.</li>
            <li>Los convenios de cooperación interinstitucional sólo serán considerados si la prestación del servicio se efectuó en establecimientos de salud del Ministerio de Salud.</li>
        </ul>
    </div>

    <!-- RESUMEN PUNTAJE CURRICULAR -->
    <div class="fw-bold mb-1">PUNTAJE CURRICULAR:</div>
    <table>
        <tr class="bg-light text-center fw-bold">
            <td width="33%">AUTOEVALUACIÓN (Puntaje)</td>
            <td width="34%">Firma del Postulante</td>
            <td width="33%">REVISIÓN (Evaluador)</td>
        </tr>
        <tr style="height: 45px;">
            <td class="text-center fw-bold"><?= $total['total_auto'] ?? '' ?></td>
            <td></td>
            <td class="text-center fw-bold"><?= $total['total_rev'] ?? '' ?></td>
        </tr>
    </table>

    <!-- BONIFICACIONES -->
    <div class="notes">
        <strong>BONIFICACIONES. -</strong> Las bonificaciones son excluyentes entre sí, en el caso de que el postulante revele más de un beneficio, solo se le otorgará el de mayor porcentaje, el cual se aplicará sobre el puntaje final obtenido:
    </div>
    <table>
        <thead>
            <tr class="bg-light text-center fw-bold">
                <th>Ítem</th>
                <th width="15%">Porcentaje</th>
                <th width="18%">Revisión</th>
                <th width="25%">Observación</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Licenciado de las Fuerzas Armadas</td>
                <td class="text-center">10%</td>
                <td class="text-center"><?= $bonificacion['ffaa_rev'] ?? '' ?></td>
                <td><?= esc($bonificacion['ffaa_obs'] ?? '') ?></td>
            </tr>
            <tr>
                <td>Personas con Discapacidad</td>
                <td class="text-center">15%</td>
                <td class="text-center"><?= $bonificacion['discapacidad_rev'] ?? '' ?></td>
                <td><?= esc($bonificacion['discapacidad_obs'] ?? '') ?></td>
            </tr>
            <tr class="bg-light fw-bold">
                <td colspan="4">Quintil de Pobreza: (Decreto Supremo Nº 007  2008  SA) HASTA SERUMS 2022-II</td>
            </tr>
            <tr>
                <td>– Quintil 1</td>
                <td class="text-center">15%</td>
                <td class="text-center"><?= $bonificacion['q1_rev'] ?? '' ?></td>
                <td><?= esc($bonificacion['q1_obs'] ?? '') ?></td>
            </tr>
            <tr>
                <td>– Quintil 2</td>
                <td class="text-center">10%</td>
                <td class="text-center"><?= $bonificacion['q2_rev'] ?? '' ?></td>
                <td><?= esc($bonificacion['q2_obs'] ?? '') ?></td>
            </tr>
            <tr>
                <td>– Quintil 3</td>
                <td class="text-center">5%</td>
                <td class="text-center"><?= $bonificacion['q3_rev'] ?? '' ?></td>
                <td><?= esc($bonificacion['q3_obs'] ?? '') ?></td>
            </tr>
            <tr>
                <td>– Quintil 4</td>
                <td class="text-center">2%</td>
                <td class="text-center"><?= $bonificacion['q4_rev'] ?? '' ?></td>
                <td><?= esc($bonificacion['q4_obs'] ?? '') ?></td>
            </tr>
            <tr>
                <td>– Quintil 5</td>
                <td class="text-center">0%</td>
                <td class="text-center"><?= $bonificacion['q5_rev'] ?? '' ?></td>
                <td><?= esc($bonificacion['q5_obs'] ?? '') ?></td>
            </tr>
            <tr class="bg-light fw-bold">
                <td colspan="4">– Grado de dificultad: (Resolución Ministerial 361-2023/MINSA. A PARTIR DE SERUMS 2023-I</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <!-- PUNTAJE TOTAL FINAL -->
    <div class="fw-bold mb-1">PUNTAJE TOTAL FINAL (Para ser llenado por el evaluador):</div>
    <table>
        <thead>
            <tr class="bg-light text-center fw-bold">
                <th>FACTORES</th>
                <th width="20%">PUNTAJE OBTENIDO</th>
                <th width="20%">PROMEDIO %</th>
                <th width="30%">OBSERVACION</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>EVALUACIÓN CURRICULAR</td>
                <td class="text-center"><?= $final['puntaje_curricular'] ?? '' ?></td>
                <td class="text-center">100%</td>
                <td><?= esc($final['obs_curricular'] ?? '') ?></td>
            </tr>
            <tr>
                <td>BONIFICACIÓN CON MAYOR PORCENTAJE</td>
                <td class="text-center"><?= $final['puntaje_bonificacion'] ?? '' ?></td>
                <td class="text-center"><?= $final['pct_bonificacion'] ?? '' ?></td>
                <td><?= esc($final['obs_bonificacion'] ?? '') ?></td>
            </tr>
            <tr class="fw-bold bg-light">
                <td>TOTAL</td>
                <td class="text-center"><?= $final['puntaje_total'] ?? '' ?></td>
                <td class="text-center"></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div class="text-end mb-2" style="margin-top: 15px;">
        Juliaca, agosto de 2026.
    </div>

    <div class="signature-box">
        _____________________________________________________________________<br>
        FIRMA Y SELLO DE LOS EVALUADORES (Currículo Vitae)
    </div>

</body>
    <?php endif;?>

<?php if($postulante['grupo_ocupacional'] == 2 || $postulante['grupo_ocupacional'] == 3):?>
<body>

    <div class="header-title">
       <?= $convocatoria['numero']??'CONVOCATORIA'?><br><br>
        FICHA DE AUTO EVALUACIÓN CURRICULAR<br>
        TECNICOS Y AUXILIARES
    </div>

    <!-- DATOS DEL POSTULANTE -->
    <table>
        <tr>
            <td width="30%" class="fw-bold">Nombres y Apellidos:</td>
            <td><?= esc($postulante['nombres_completos'] ?? '') ?></td>
        </tr>
        <tr>
            <td class="fw-bold">N° DNI:</td>
            <td><?= esc($postulante['documento'] ?? '') ?></td>
        </tr>
        <tr>
            <td class="fw-bold">Cargo al que postula:</td>
            <td><?= esc($postulante['cargo'] ?? '') ?></td>
        </tr>
        <tr>
            <td class="fw-bold">Código de plaza AIRHSP:</td>
            <td><?= esc($postulante['codigo_airhsp'] ?? '-') ?></td>
        </tr>
    </table>

    <!-- 1. EVALUACION CURRICULAR -->
    <div class="fw-bold mb-1">1.- EVALUACION CURRICULAR</div>
    <table>
        <thead>
            <tr class="bg-light text-center fw-bold">
                <th>RUBRO</th>
                <th width="18%">PUNTAJE MÁXIMO</th>
                <th width="18%">AUTO EVALUACIÓN</th>
                <th width="18%">REVISIÓN</th>
            </tr>
        </thead>
        <tbody>
            <tr class="fw-bold bg-light">
                <td>A. Títulos y grados</td>
                <td class="text-center">40 puntos</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>a. Título profesional</td>
                <td class="text-center">40 puntos</td>
                <td class="text-center"><?= $profesion['titulo_profesional_auto'] ?? '' ?></td>
                <td class="text-center"><?= $profesion['titulo_profesional_rev'] ?? '' ?></td>
            </tr>
            
            <tr class="fw-bold bg-light">
                <td>B. Diplomados, eventos y cursos de capacitación</td>
                <td class="text-center">20 puntos</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>a. Cursos iguales o mayores a 500 hrs. lectivas, 2.5 puntos por cada certificado máximo 4 certificados</td>
                <td class="text-center">10 puntos</td>
                <td class="text-center"><?= $capacitaciones['cursos_mayores_auto'] ?? '' ?></td>
                <td class="text-center"><?= $capacitaciones['cursos_mayores_rev'] ?? '' ?></td>
            </tr>
            <tr>
                <td>b. Cursos menores a 500 hrs. lectivas, 0.02 punto por cada hora lectiva se calificarán máximo 500 horas acumuladas.</td>
                <td class="text-center">10 puntos</td>
                <td class="text-center"><?= $capacitaciones['cursos_menores_auto'] ?? '' ?></td>
                <td class="text-center"><?= $capacitaciones['cursos_menores_rev'] ?? '' ?></td>
            </tr>
            <tr class="fw-bold bg-light">
                <td>C. Identificación institucional</td>
                <td class="text-center">10 puntos</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>a. Resolución de Encargo o Designación 1 punto por cada resolución (máximo 5)</td>
                <td class="text-center">5 puntos</td>
                <td class="text-center"><?= $identificacion['res_encargo_auto'] ?? '' ?></td>
                <td class="text-center"><?= $identificacion['res_encargo_rev'] ?? '' ?></td>
            </tr>
            <tr>
                <td>b. Resolución de Felicitación 1 punto por cada resolución (máximo 5)</td>
                <td class="text-center">5 puntos</td>
                <td class="text-center"><?= $identificacion['res_felicitacion_auto'] ?? '' ?></td>
                <td class="text-center"><?= $identificacion['res_felicitacion_rev'] ?? '' ?></td>
            </tr>
            <tr class="fw-bold bg-light">
                <td>D. Experiencia laboral</td>
                <td class="text-center">30 puntos</td>
                <td class="text-center"></td>
                <td class="text-center"></td>

            </tr>
            <tr>
                <td>a. Experiencia Laboral General* 06 puntos por año (Máximo 5 años)</td>
                <td class="text-center">30 puntos</td>
                <td class="text-center"><?= $experiencia['exp_general_auto'] ?? '' ?></td>
                <td class="text-center"><?= $experiencia['exp_general_rev'] ?? '' ?></td>
            </tr>
            <tr class="fw-bold bg-light">
                <td class="text-end">TOTAL</td>
                <td class="text-center">100 puntos</td>
                <td class="text-center"><?= $total['total_auto'] ?? '' ?></td>
                <td class="text-center"><?= $total['total_rev'] ?? '' ?></td>
            </tr>
        </tbody>
    </table>

    <!-- NOTAS APOSTILLADAS -->
    <div class="notes">
        <ul>
            <li>Solo se calificarán los certificados con una antigüedad no mayor de 5 años y posteriores a la expedición del Título Profesional y/o Técnico.</li>
            <li>En los cursos que no especifiquen horas se tomará la equivalencia de 17 horas por cada crédito.</li>
        </ul>
        <br>
        <strong>B. EXPERIENCIA LABORAL (Máximo 20 puntos)</strong>
        <ul>
            <li>Experiencia laboral General: Contratos con el Ministerio de Salud – MINSA y sus órganos desconcentrados, se calificará los contratos bajo el D.L. N° 276 con Resoluciones y Contratos bajo el D.L. 1057 (CAS) y D.L. N° 728 (Régimen privado). No se calificarán los contratos por locación de servicios, ni constancias.</li>
            <li>Los convenios de cooperación interinstitucional sólo serán considerados si la prestación del servicio se efectuó en establecimientos de salud del Ministerio de Salud.</li>
        </ul>
    </div>

    <!-- RESUMEN PUNTAJE CURRICULAR -->
    <div class="fw-bold mb-1">PUNTAJE CURRICULAR:</div>
    <table>
        <tr class="bg-light text-center fw-bold">
            <td width="33%">AUTOEVALUACIÓN (Puntaje)</td>
            <td width="34%">Firma del Postulante</td>
            <td width="33%">REVISIÓN (Evaluador)</td>
        </tr>
        <tr style="height: 45px;">
            <td class="text-center fw-bold"><?= $total['total_auto'] ?? '' ?></td>
            <td></td>
            <td class="text-center fw-bold"><?= $total['total_rev'] ?? '' ?></td>
        </tr>
    </table>

    <!-- BONIFICACIONES -->
    <div class="notes">
        <strong>BONIFICACIONES. -</strong> Las bonificaciones son excluyentes entre sí, en el caso de que el postulante revele más de un beneficio, solo se le otorgará el de mayor porcentaje, el cual se aplicará sobre el puntaje final obtenido:
    </div>
    <table>
        <thead>
            <tr class="bg-light text-center fw-bold">
                <th>Ítem</th>
                <th width="15%">Porcentaje</th>
                <th width="18%">Revisión</th>
                <th width="25%">Observación</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Licenciado de las Fuerzas Armadas</td>
                <td class="text-center">10%</td>
                <td class="text-center"><?= $bonificacion['ffaa_rev'] ?? '' ?></td>
                <td><?= esc($bonificacion['ffaa_obs'] ?? '') ?></td>
            </tr>
            <tr>
                <td>Personas con Discapacidad</td>
                <td class="text-center">15%</td>
                <td class="text-center"><?= $bonificacion['discapacidad_rev'] ?? '' ?></td>
                <td><?= esc($bonificacion['discapacidad_obs'] ?? '') ?></td>
            </tr>
            

        </tbody>
    </table>

    <!-- PUNTAJE TOTAL FINAL -->
    <div class="fw-bold mb-1">PUNTAJE TOTAL FINAL (Para ser llenado por el evaluador):</div>
    <table>
        <thead>
            <tr class="bg-light text-center fw-bold">
                <th>FACTORES</th>
                <th width="20%">PUNTAJE OBTENIDO</th>
                <th width="20%">PROMEDIO %</th>
                <th width="30%">OBSERVACION</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>EVALUACIÓN CURRICULAR</td>
                <td class="text-center"><?= $final['puntaje_curricular'] ?? '' ?></td>
                <td class="text-center">100%</td>
                <td><?= esc($final['obs_curricular'] ?? '') ?></td>
            </tr>
            <tr>
                <td>BONIFICACIÓN CON MAYOR PORCENTAJE</td>
                <td class="text-center"><?= $final['puntaje_bonificacion'] ?? '' ?></td>
                <td class="text-center"><?= $final['pct_bonificacion'] ?? '' ?></td>
                <td><?= esc($final['obs_bonificacion'] ?? '') ?></td>
            </tr>
            <tr class="fw-bold bg-light">
                <td>TOTAL</td>
                <td class="text-center"><?= $final['puntaje_total'] ?? '' ?></td>
                <td class="text-center"></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div class="text-end mb-2" style="margin-top: 15px;">
        Juliaca, agosto de 2026.
    </div>

    <div class="signature-box">
        _____________________________________________________________________<br>
        FIRMA Y SELLO DE LOS EVALUADORES (Currículo Vitae)
    </div>

</body>
<?php endif;
?>



</html>