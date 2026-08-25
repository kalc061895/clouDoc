<?php

namespace Modules\Seleccion\Services;

use Modules\Seleccion\Models\PostulanteModel;
use Modules\Seleccion\Models\PostulacionModel;
use Modules\Seleccion\Models\ConvocatoriaModel;
use Modules\Seleccion\Models\PostulanteFormacionModel;
use Modules\Seleccion\Services\PostulanteProfesionService;
use Modules\Seleccion\Models\PostulanteProfesionModel;
use Modules\Seleccion\Models\PostulanteCapacitacionModel;
use Modules\Seleccion\Models\PostulanteExperienciaModel;
use Modules\Seleccion\Models\PostulantesOtroModel;


class GenerarDocumentoService
{
    protected $postulanteModel;

    public function __construct()
    {
        $this->postulanteModel = new PostulanteModel();
    }

    public function obtenerDatosSolicitud($convocatoriaId)
    {
        $convocatoriaModel = new ConvocatoriaModel();

        $postulante = $convocatoriaModel

            ->join('selec_convocatoria_cargos', 'selec_convocatoria_cargos.cco_con_ide = selec_convocatorias.con_ide', 'left')
            ->join('selec_postulaciones', 'selec_postulaciones.pto_cco_ide = selec_convocatoria_cargos.cco_ide', 'left')
            ->join('selec_postulantes', 'selec_postulantes.pos_ide = selec_postulaciones.pto_pos_ide', 'left')
            ->join('selec_cargos', 'selec_cargos.car_ide = selec_convocatoria_cargos.cco_car_ide', 'left')
            ->where('con_ide', $convocatoriaId)
            ->where('pos_user_id', auth()->id())
            ->first();

        if (!$postulante) {
            return [];
        }

        return [
            'codigo_inscripcion' => $postulante['pto_codigo'],
            'cargo' => $postulante['car_denominacion'] ?? '____________________',
            'nombres' => $postulante['pos_nombres'],
            'apellidos' => trim($postulante['pos_apellido_paterno'] . ' ' . $postulante['pos_apellido_materno']),
            'documento' => $postulante['pos_documento'],
            'direccion' => $postulante['pos_direccion'] ?? '____________________',
            'telefono' => $postulante['pos_telefono'] ?? '',
            'email' => $postulante['pos_email'] ?? '',
            'fecha_actual' => date('d/m/Y'),
            'num_convocatoria' => $postulante['con_nombre'] ?? '', // Dinámico o pasado por parámetro
        ];
    }

    public function obtenerDatosFichaUnica($convocatoriaId)
    {
        $convocatoriaModel = new ConvocatoriaModel();

        $postulante = $convocatoriaModel

            ->join('selec_convocatoria_cargos', 'selec_convocatoria_cargos.cco_con_ide = selec_convocatorias.con_ide', 'left')
            ->join('selec_postulaciones', 'selec_postulaciones.pto_cco_ide = selec_convocatoria_cargos.cco_ide', 'left')
            ->join('selec_postulantes', 'selec_postulantes.pos_ide = selec_postulaciones.pto_pos_ide', 'left')
            ->join('selec_cargos', 'selec_cargos.car_ide = selec_convocatoria_cargos.cco_car_ide', 'left')
            ->where('con_ide', $convocatoriaId)
            ->where('pos_user_id', auth()->id())
            ->first();

        if (!$postulante) {
            return [];
        }

        // Simulación/Consulta de tablas secundarias relacionándolas con $postulanteId
        // $formacionModel = new FormacionModel();
        $postulanteProfesionService = new PostulanteProfesionService();
        $profesion = $postulanteProfesionService->getByPostulanteId($postulante['pos_ide']);

        $formacion = (new PostulanteFormacionModel())->where('pfo_pos_ide', $postulante['pos_ide'] ?? 0)
            ->join('selec_niveles_formacion', 'nfo_ide = pfo_nfo_ide', 'left')
            ->join('selec_expediente_documentos', 'exd_ide = pfo_documento_ide', 'left')
            ->findAll();


        $postulanteProfesionService = new PostulanteProfesionService();
        $profesion = $postulanteProfesionService->getByPostulanteId($postulante['pos_ide']);

        $profesiones = (new PostulanteProfesionModel())->where('ppr_pos_ide', $postulante['pos_ide'] ?? 0)
            ->join('selec_expediente_documentos', 'exd_ide = ppr_documento_ide', 'left')
            ->join('selec_profesiones', 'pro_ide = ppr_pro_ide', 'left')
            ->findAll();


        // $capacitacionModel = new CapacitacionModel();
        $capacitacion = (new PostulanteCapacitacionModel())->where('pca_pos_ide', $postulante['pos_ide'] ?? 0)
            ->join('selec_expediente_documentos', 'exd_ide = pca_documento_ide', 'left')
            ->findAll();

        $experiencia = (new PostulanteExperienciaModel())->where('pex_pos_ide', $postulante['pos_ide'] ?? 0)->join('selec_modalidades_vinculo', 'mvi_ide = pex_mvi_ide', 'left')->join('selec_expediente_documentos', 'exd_ide = pex_documento_ide', 'left')
            ->findAll();

        $identificacion = (new PostulantesOtroModel())->where('otr_pos_ide', $postulante['pos_ide'] ?? 0)
            ->join('selec_expediente_documentos', 'exd_ide = otr_documento_ide', 'left')
            ->findAll();


        return [
            'postulante' => [
                'nombres_completos' => trim($postulante['pos_nombres'] . ' ' . $postulante['pos_apellido_paterno'] . ' ' . $postulante['pos_apellido_materno']),
                'documento' => $postulante['pos_documento'],
                'fecha_nacimiento' => $postulante['pos_fecha_nacimiento'],
                'sexo' => ($postulante['pos_sexo'] == 'M') ? 'Masculino' : 'Femenino',
                'direccion' => $postulante['pos_direccion'],
                'telefono' => $postulante['pos_telefono'],
                'email' => $postulante['pos_email'],
            ],
            'colegiatura' =>
                [
                    'colegio' => $profesion['ppr_titulo'] ?? '-',
                    'numero' => $profesion['ppr_colegitura'] ?? '-',
                    'estado' => $profesion['ppr_habilitacion'] ?? '-',
                    'fecha_colegiado' => $profesion['ppr_fecha'] ?? '-',
                ],

            'profesion' => $profesiones,
            'formacion' => $formacion,
            'capacitaciones' => $capacitacion,
            'experiencia' => $experiencia,
            'identificacion' => $identificacion,
            'convocatoria' => [
                'numero' => 'CAS N° 001-2026',
                'ejecutora' => 'RED DE SALUD SAN ROMÁN - UE 403',
                'fecha_imp' => date('d/m/Y H:i:s')
            ]
        ];
    }

    public function obtenerDatosAutoevaluacion($convocatoriaId)
    {
        $convocatoriaModel = new ConvocatoriaModel();

        $postulante = $convocatoriaModel

            ->join('selec_convocatoria_cargos', 'selec_convocatoria_cargos.cco_con_ide = selec_convocatorias.con_ide', 'left')
            ->join('selec_postulaciones', 'selec_postulaciones.pto_cco_ide = selec_convocatoria_cargos.cco_ide', 'left')
            ->join('selec_postulantes', 'selec_postulantes.pos_ide = selec_postulaciones.pto_pos_ide', 'left')
            ->join('selec_cargos', 'selec_cargos.car_ide = selec_convocatoria_cargos.cco_car_ide', 'left')
            ->where('con_ide', $convocatoriaId)
            ->where('pos_user_id', auth()->id())
            ->first();

        if (!$postulante) {
            return [];
        }
        // por ahora lo haremos manual
        // si el postulante pertenece a profesional
        if (!$postulante['car_gru_ide'] != 1) {
            // Simulación/Consulta de tablas secundarias relacionándolas con $postulanteId
            // $formacionModel = new FormacionModel();
            $postulanteProfesionService = new PostulanteProfesionService();
            $profesion = $postulanteProfesionService->getByPostulanteId($postulante['pos_ide']);




            $postulanteProfesionService = new PostulanteProfesionService();
            $profesion = $postulanteProfesionService->getByPostulanteId($postulante['pos_ide']);

            $profesiones = (new PostulanteProfesionModel())->where('ppr_pos_ide', $postulante['pos_ide'] ?? 0)
                ->join('selec_expediente_documentos', 'exd_ide = ppr_documento_ide', 'left')
                ->join('selec_profesiones', 'pro_ide = ppr_pro_ide', 'left')
                ->findAll();

            $resumen['titulo'] = 0;

            if ($profesiones) {
                $resumen['titulo'] = 30;
            }


            $formacion = (new PostulanteFormacionModel())->where('pfo_pos_ide', $postulante['pos_ide'] ?? 0)
                ->join('selec_niveles_formacion', 'nfo_ide = pfo_nfo_ide', 'left')
                ->join('selec_expediente_documentos', 'exd_ide = pfo_documento_ide', 'left')
                ->findAll();

            if ($formacion) {
                foreach ($formacion as $for) {
                    switch ($for['pfo_nfo_ide']) {
                        case 8: //MAESTRIA
                            $resumen['maestria'] = 5;
                            break;
                        case 7: //UNIV_TIT
                            $resumen['titulo'] = 30;
                            break;
                        case 9: //DOCTORADO
                            $resumen['doctorado'] = 10;
                            break;
                        case 10: //ESPECIALIZACION
                            $resumen['especialidad'] = 10;
                            break;
                    }
                }
            }



            // $capacitacionModel = new CapacitacionModel();
            $capacitacion = (new PostulanteCapacitacionModel())->where('pca_pos_ide', $postulante['pos_ide'] ?? 0)
                ->join('selec_expediente_documentos', 'exd_ide = pca_documento_ide', 'left')
                ->findAll();

            if ($capacitacion) {
                $horas = 0;
                $maximo_mayores = 0;
                foreach ($capacitacion as $cap) {
                    if ($cap['pca_horas'] >= 500) {
                        $maximo_mayores++;
                    } else {
                        $horas += $cap['pca_horas'];
                    }

                }
                $resumen['capa_mayor'] = min($maximo_mayores, 2) * 2;
                $resumen['capa_menor'] = min($horas, 500) * 0.02;
            }

            $experiencia = (new PostulanteExperienciaModel())->where('pex_pos_ide', $postulante['pos_ide'] ?? 0)->join('selec_modalidades_vinculo', 'mvi_ide = pex_mvi_ide', 'left')->join('selec_expediente_documentos', 'exd_ide = pex_documento_ide', 'left')
                ->findAll();
            if ($experiencia) {
                $dias = 0;

                foreach ($experiencia as $cap) {
                    $dias += $cap['pex_dias_declarados'];

                }
                $meses = $dias / 30;
                $anios = $meses / 12;
                $ptje = round($meses * 0.33);
                $resumen['experiencia'] = min($ptje, 20);
            }
            $resumen['total'] = array_sum($resumen);
            $identificacion = (new PostulantesOtroModel())->where('otr_pos_ide', $postulante['pos_ide'] ?? 0)
                ->join('selec_expediente_documentos', 'exd_ide = otr_documento_ide', 'left')
                ->findAll();

            if ($identificacion) {
                $felic = 0;
                $encar = 0;
                foreach ($identificacion as $for) {
                    switch ($for['otr_tipo']) {
                        case 'FELICITACION': //UNIV_TIT
                            $felic++;
                            break;
                        case 'ENCARGATURA': //UNIV_TIT
                            $felic++;
                            break;
                        case 'FFAA': //UNIV_TIT
                            $resumen['ffaa'] = 1;
                            break;
                        case 'CONADIS': //UNIV_TIT
                            $resumen['disc'] = 1;
                            break;
                        case 'QUINTIL 1': //UNIV_TIT
                            $resumen['quintil'] = 1;
                            break;
                        case 'QUINTIL 2': //UNIV_TIT
                            $resumen['quintil'] = 2;
                            break;
                        case 'QUINTIL 3': //UNIV_TIT
                            $resumen['quintil'] = 3;
                            break;
                        case 'QUINTIL 4': //UNIV_TIT
                            $resumen['quintil'] = 4;
                            break;
                        case 'QUINTIL 5': //UNIV_TIT
                            $resumen['quintil'] = 5;
                            break;
                        case 'GD 1': //UNIV_TIT
                            $resumen['dificultad'] = 1;
                            break;
                        case 'GD 2': //UNIV_TIT
                            $resumen['dificultad'] = 2;
                            break;
                        case 'GD 3': //UNIV_TIT
                            $resumen['dificultad'] = 3;
                            break;
                        case 'GD 4': //UNIV_TIT
                            $resumen['dificultad'] = 4;
                            break;
                        case 'GD 5': //UNIV_TIT
                            $resumen['dificultad'] = 5;
                            break;
                    }
                }
                $resumen['ide_encar'] = min($encar, 5) * 0.5;
                $resumen['ide_felic'] = min($felic, 5) * 0.5;
            }
        }
        // en este caso perteneceria a tecnico o auxiliar
        else {
            // Simulación/Consulta de tablas secundarias relacionándolas con $postulanteId
            // $formacionModel = new FormacionModel();
            $postulanteProfesionService = new PostulanteProfesionService();
            $profesion = $postulanteProfesionService->getByPostulanteId($postulante['pos_ide']);
            $postulanteProfesionService = new PostulanteProfesionService();
            $profesion = $postulanteProfesionService->getByPostulanteId($postulante['pos_ide']);

            $profesiones = (new PostulanteProfesionModel())->where('ppr_pos_ide', $postulante['pos_ide'] ?? 0)
                ->join('selec_expediente_documentos', 'exd_ide = ppr_documento_ide', 'left')
                ->join('selec_profesiones', 'pro_ide = ppr_pro_ide', 'left')
                ->findAll();

            $resumen['titulo'] = 0;

            if ($profesiones) {
                $resumen['titulo'] = 40;
            }


            $formacion = (new PostulanteFormacionModel())->where('pfo_pos_ide', $postulante['pos_ide'] ?? 0)
                ->join('selec_niveles_formacion', 'nfo_ide = pfo_nfo_ide', 'left')
                ->join('selec_expediente_documentos', 'exd_ide = pfo_documento_ide', 'left')
                ->findAll();

            if ($formacion) {
                foreach ($formacion as $for) {
                    switch ($for['pfo_nfo_ide']) {
                        case 2: //MAESTRIA
                            $resumen['titulo'] = 40;
                            break;
                        
                    }
                }
            }



            // $capacitacionModel = new CapacitacionModel();
            $capacitacion = (new PostulanteCapacitacionModel())->where('pca_pos_ide', $postulante['pos_ide'] ?? 0)
                ->join('selec_expediente_documentos', 'exd_ide = pca_documento_ide', 'left')
                ->findAll();

            if ($capacitacion) {
                $horas = 0;
                $maximo_mayores = 0;
                foreach ($capacitacion as $cap) {
                    if ($cap['pca_horas'] >= 500) {
                        $maximo_mayores++;
                    } else {
                        $horas += $cap['pca_horas'];
                    }

                }
                $resumen['capa_mayor'] = min($maximo_mayores, 4) * 2.5;
                $resumen['capa_menor'] = min($horas, 500) * 0.02;
            }

            $experiencia = (new PostulanteExperienciaModel())->where('pex_pos_ide', $postulante['pos_ide'] ?? 0)->join('selec_modalidades_vinculo', 'mvi_ide = pex_mvi_ide', 'left')->join('selec_expediente_documentos', 'exd_ide = pex_documento_ide', 'left')
                ->findAll();
            if ($experiencia) {
                $dias = 0;

                foreach ($experiencia as $cap) {
                    $dias += $cap['pex_dias_declarados'];

                }
                $meses = $dias / 30;
                $ptje = round($meses * 0.5);
                $resumen['experiencia'] = min($ptje, 30);
            }

            $resumen['total'] = array_sum($resumen);

            $identificacion = (new PostulantesOtroModel())->where('otr_pos_ide', $postulante['pos_ide'] ?? 0)
                ->join('selec_expediente_documentos', 'exd_ide = otr_documento_ide', 'left')
                ->findAll();

            if ($identificacion) {
                $felic = 0;
                $encar = 0;
                foreach ($identificacion as $for) {
                    switch ($for['otr_tipo']) {
                        case 'FELICITACION': //UNIV_TIT
                            $felic++;
                            break;
                        case 'ENCARGATURA': //UNIV_TIT
                            $felic++;
                            break;
                        case 'FFAA': //UNIV_TIT
                            $resumen['ffaa'] = 1;
                            break;
                        case 'CONADIS': //UNIV_TIT
                            $resumen['disc'] = 1;
                            break;
                        case 'QUINTIL 1': //UNIV_TIT
                            $resumen['quintil'] = 1;
                            break;
                        case 'QUINTIL 2': //UNIV_TIT
                            $resumen['quintil'] = 2;
                            break;
                        case 'QUINTIL 3': //UNIV_TIT
                            $resumen['quintil'] = 3;
                            break;
                        case 'QUINTIL 4': //UNIV_TIT
                            $resumen['quintil'] = 4;
                            break;
                        case 'QUINTIL 5': //UNIV_TIT
                            $resumen['quintil'] = 5;
                            break;
                        case 'GD 1': //UNIV_TIT
                            $resumen['dificultad'] = 1;
                            break;
                        case 'GD 2': //UNIV_TIT
                            $resumen['dificultad'] = 2;
                            break;
                        case 'GD 3': //UNIV_TIT
                            $resumen['dificultad'] = 3;
                            break;
                        case 'GD 4': //UNIV_TIT
                            $resumen['dificultad'] = 4;
                            break;
                        case 'GD 5': //UNIV_TIT
                            $resumen['dificultad'] = 5;
                            break;
                    }
                }
                $resumen['ide_encar'] = min($encar, 5) * 1;
                $resumen['ide_felic'] = min($felic, 5) * 1;
            }
        }
        $resumen['total'] = $resumen['total']+($resumen['ide_encar'] ?? 0)+($resumen['ide_felic'] ?? 0);




        return [
            'convocatoria' => [
                'numero' => $postulante['con_nombre'],
                'ejecutora' => 'RED DE SALUD SAN ROMÁN - UE 403',
                'fecha_imp' => date('d/m/Y H:i:s')
            ],
            'postulante' => [
                'nombres_completos' => trim($postulante['pos_nombres'] . ' ' . $postulante['pos_apellido_paterno'] . ' ' . $postulante['pos_apellido_materno']),
                'documento' => $postulante['pos_documento'],
                'fecha_nacimiento' => $postulante['pos_fecha_nacimiento'],
                'sexo' => ($postulante['pos_sexo'] == 'M') ? 'Masculino' : 'Femenino',
                'direccion' => $postulante['pos_direccion'],
                'telefono' => $postulante['pos_telefono'],
                'email' => $postulante['pos_email'],
                'cargo' => $postulante['car_denominacion'] ?? '-',
                'grupo_ocupacional' => $postulante['car_gru_ide'] ?? 0,

            ],
            'profesion' => [
                'titulo_profesional_auto' => $resumen['titulo'] ?? 0,
                'titulo_especialidad_auto' => $resumen['especialidad'] ?? 0,
                'doctorado_auto' => $resumen['doctorado'] ?? 0,
                'maestria_auto' => $resumen['maestria'] ?? 0,
            ],
            'capacitaciones' => [
                'cursos_mayores_auto' => $resumen['capa_mayor'] ?? 0,
                'cursos_menores_auto' => $resumen['capa_menor'] ?? 0,

            ],
            'identificacion' => [
                'res_encargo_auto' => $resumen['ide_encar'] ?? 0,
                'res_felicitacion_auto' => $resumen['ide_felic'] ?? 0,
            ],
            'experiencia' => [
                'exp_general_auto' => $resumen['experiencia'] ?? 0,
            ],
            'bonificacion' => [
                'ffaa_rev' => $resumen['ffaa'] ?? 0,
                'discapacidad_rev' => $resumen['discapacidad'] ?? 0,
                'quintil' => $resumen['quintil'] ?? 0,
            ],
            'total' => [
                'total_auto' => $resumen['total'] ?? 0,
            ],
            'resumen' => $resumen,

        ];
    }
    public function obtenerDatosInscripcion($convocatoriaId)
    {
        $convocatoriaModel = new ConvocatoriaModel();

        $postulante = $convocatoriaModel

            ->join('selec_convocatoria_cargos', 'selec_convocatoria_cargos.cco_con_ide = selec_convocatorias.con_ide', 'left')
            ->join('selec_postulaciones', 'selec_postulaciones.pto_cco_ide = selec_convocatoria_cargos.cco_ide', 'left')
            ->join('selec_postulantes', 'selec_postulantes.pos_ide = selec_postulaciones.pto_pos_ide', 'left')
            ->join('selec_cargos', 'selec_cargos.car_ide = selec_convocatoria_cargos.cco_car_ide', 'left')
            ->where('con_ide', $convocatoriaId)
            ->where('pos_user_id', auth()->id())
            ->first();

        if (!$postulante) {
            return [];
        }

        // Simulación/Consulta de tablas secundarias relacionándolas con $postulanteId
        // $formacionModel = new FormacionModel();
        $postulanteProfesionService = new PostulanteProfesionService();
        $profesion = $postulanteProfesionService->getByPostulanteId($postulante['pos_ide']);

        $formacion = (new PostulanteFormacionModel())->where('pfo_pos_ide', $postulante['pos_ide'] ?? 0)
            ->join('selec_niveles_formacion', 'nfo_ide = pfo_nfo_ide', 'left')
            ->join('selec_expediente_documentos', 'exd_ide = pfo_documento_ide', 'left')
            ->findAll();


        $postulanteProfesionService = new PostulanteProfesionService();
        $profesion = $postulanteProfesionService->getByPostulanteId($postulante['pos_ide']);

        $profesiones = (new PostulanteProfesionModel())->where('ppr_pos_ide', $postulante['pos_ide'] ?? 0)
            ->join('selec_expediente_documentos', 'exd_ide = ppr_documento_ide', 'left')
            ->join('selec_profesiones', 'pro_ide = ppr_pro_ide', 'left')
            ->findAll();


        // $capacitacionModel = new CapacitacionModel();
        $capacitacion = (new PostulanteCapacitacionModel())->where('pca_pos_ide', $postulante['pos_ide'] ?? 0)
            ->join('selec_expediente_documentos', 'exd_ide = pca_documento_ide', 'left')
            ->findAll();

        $experiencia = (new PostulanteExperienciaModel())->where('pex_pos_ide', $postulante['pos_ide'] ?? 0)->join('selec_modalidades_vinculo', 'mvi_ide = pex_mvi_ide', 'left')->join('selec_expediente_documentos', 'exd_ide = pex_documento_ide', 'left')
            ->findAll();

        $identificacion = (new PostulantesOtroModel())->where('otr_pos_ide', $postulante['pos_ide'] ?? 0)
            ->join('selec_expediente_documentos', 'exd_ide = otr_documento_ide', 'left')
            ->findAll();


        return [
            'postulacion' => [
                'nombres_completos' => trim($postulante['pos_nombres'] . ' ' . $postulante['pos_apellido_paterno'] . ' ' . $postulante['pos_apellido_materno']),
                'documento' => $postulante['pos_documento'],
                'fecha_nacimiento' => $postulante['pos_fecha_nacimiento'],
                'sexo' => ($postulante['pos_sexo'] == 'M') ? 'Masculino' : 'Femenino',
                'direccion' => $postulante['pos_direccion'],
                'telefono' => $postulante['pos_telefono'],
                'email' => $postulante['pos_email'],
            ],
            'postulante' =>$postulante,
            'colegiatura' =>
                [
                    'colegio' => $profesion['ppr_titulo'] ?? '-',
                    'numero' => $profesion['ppr_colegitura'] ?? '-',
                    'estado' => $profesion['ppr_habilitacion'] ?? '-',
                    'fecha_colegiado' => $profesion['ppr_fecha'] ?? '-',
                ],

            'profesion' => $profesiones,
            'formacion' => $formacion,
            'capacitaciones' => $capacitacion,
            'experiencia' => $experiencia,
            'identificacion' => $identificacion,
            'convocatoria' => [
                'numero' => 'CAS N° 001-2026',
                'ejecutora' => 'RED DE SALUD SAN ROMÁN - UE 403',
                'fecha_imp' => date('d/m/Y H:i:s')
            ]
        ];
    }
}
