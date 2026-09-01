<?php

namespace Modules\Legajos\Controllers;

use App\Core\Controllers\BaseModuleController;
use CodeIgniter\HTTP\ResponseInterface;

use Modules\Legajos\Models\ServidorModel;
use Modules\Legajos\Models\LegajoSeccionModel;
use Modules\Legajos\Models\FamiliarModel;
use Modules\Legajos\Models\FormacionModel;
use Modules\Legajos\Models\ExperienciaModel;
use Modules\Legajos\Models\MovimientoModel;
use Modules\Legajos\Models\CapacitacionModel;
use Modules\Legajos\Models\SancionMeritoModel;
use Modules\Legajos\Models\DocumentoDigitalModel;

class LegajosController extends BaseModuleController
{
    protected $servidorModel;
    protected $seccionModel;
    protected $familiarModel;
    protected $formacionModel;
    protected $experienciaModel;
    protected $movimientoModel;
    protected $capacitacionModel;
    protected $sancionMeritoModel;
    protected $documentoDigitalModel;

    public function __construct()
    {
        $this->servidorModel         = new ServidorModel();
        $this->seccionModel          = new LegajoSeccionModel();
        $this->familiarModel         = new FamiliarModel();
        $this->formacionModel        = new FormacionModel();
        $this->experienciaModel      = new ExperienciaModel();
        $this->movimientoModel       = new MovimientoModel();
        $this->capacitacionModel     = new CapacitacionModel();
        $this->sancionMeritoModel    = new SancionMeritoModel();
        $this->documentoDigitalModel = new DocumentoDigitalModel();
    }

    /**
     * Vista Principal: Directorio y Listado de Servidores Públicos
     */
    public function index()
    {
        $estadisticas = $this->servidorModel->getEstadisticas();
        $secciones    = $this->seccionModel->getSeccionesActivas();

        $data = [
            'titulo'       => 'Legajo Personal de Servidores Públicos',
            'estadisticas' => $estadisticas,
            'secciones'    => $secciones,
        ];

        return view('Modules\Legajos\Views\index', $data);
    }

    /**
     * Endpoint AJAX: Listado para DataTables
     */
    public function listar(): ResponseInterface
    {
        $regimen     = $this->request->getGet('regimen');
        $estado      = $this->request->getGet('estado');
        $dependencia = $this->request->getGet('dependencia');

        $builder = $this->servidorModel->asArray();

        if (!empty($regimen)) {
            $builder->where('ser_regimen_laboral', $regimen);
        }
        if (!empty($estado)) {
            $builder->where('ser_estado', $estado);
        }
        if (!empty($dependencia)) {
            $builder->like('ser_dependencia', $dependencia);
        }

        $servidores = $builder->orderBy('ser_apellido_paterno', 'ASC')->findAll();

        return $this->response->setJSON([
            'data' => $servidores,
        ]);
    }

    /**
     * Vista Detallada del Legajo Personal con Pestañas Normativas
     */
    public function ver(int $servidorId)
    {
        $servidor = $this->servidorModel->find($servidorId);

        if (!$servidor) {
            return redirect()->to(base_url('legajos'))->with('error', 'Servidor público no encontrado.');
        }

        $data = [
            'titulo'       => 'Legajo Personal: ' . $this->servidorModel->getNombreCompleto($servidor),
            'servidor'     => $servidor,
            'secciones'    => $this->seccionModel->getSeccionesActivas(),
            'familiares'   => $this->familiarModel->getPorServidor($servidorId),
            'formaciones'  => $this->formacionModel->getPorServidor($servidorId),
            'experiencias' => $this->experienciaModel->getPorServidor($servidorId),
            'movimientos'  => $this->movimientoModel->getPorServidor($servidorId),
            'capacitaciones' => $this->capacitacionModel->getPorServidor($servidorId),
            'meritosSanciones' => $this->sancionMeritoModel->getPorServidor($servidorId),
            'documentos'   => $this->documentoDigitalModel->getPorServidor($servidorId),
        ];

        return view('Modules\Legajos\Views\ver', $data);
    }

    /**
     * Obtener datos de un servidor en JSON
     */
    public function obtenerServidor(int $id): ResponseInterface
    {
        $servidor = $this->servidorModel->find($id);
        if (!$servidor) {
            return $this->jsonResponse('error', 'Servidor no encontrado.', [], 404);
        }

        return $this->jsonResponse('success', 'Servidor recuperado.', $servidor);
    }

    /**
     * Crear o Actualizar Datos del Servidor Público
     */
    public function guardarServidor(): ResponseInterface
    {
        $id = $this->request->getPost('ser_ide');

        $data = [
            'ser_tipo_documento'      => $this->request->getPost('ser_tipo_documento') ?? 'DNI',
            'ser_numero_documento'    => trim($this->request->getPost('ser_numero_documento') ?? ''),
            'ser_ruc'                 => trim($this->request->getPost('ser_ruc') ?? ''),
            'ser_nombres'             => trim($this->request->getPost('ser_nombres') ?? ''),
            'ser_apellido_paterno'    => trim($this->request->getPost('ser_apellido_paterno') ?? ''),
            'ser_apellido_materno'    => trim($this->request->getPost('ser_apellido_materno') ?? ''),
            'ser_sexo'                => $this->request->getPost('ser_sexo') ?? 'M',
            'ser_fecha_nacimiento'    => $this->request->getPost('ser_fecha_nacimiento') ?: null,
            'ser_estado_civil'        => $this->request->getPost('ser_estado_civil') ?? 'SOLTERO',
            'ser_grupo_sanguineo'     => $this->request->getPost('ser_grupo_sanguineo') ?: null,
            'ser_celular'             => $this->request->getPost('ser_celular') ?: null,
            'ser_telefono_fijo'       => $this->request->getPost('ser_telefono_fijo') ?: null,
            'ser_email_institucional' => $this->request->getPost('ser_email_institucional') ?: null,
            'ser_email_personal'      => $this->request->getPost('ser_email_personal') ?: null,
            'ser_direccion'           => $this->request->getPost('ser_direccion') ?: null,
            'ser_ubigeo'              => $this->request->getPost('ser_ubigeo') ?: null,
            'ser_regimen_laboral'     => $this->request->getPost('ser_regimen_laboral') ?? 'D.L. 1057 (CAS)',
            'ser_condicion_laboral'   => $this->request->getPost('ser_condicion_laboral') ?? 'CONTRATADO',
            'ser_cargo'               => trim($this->request->getPost('ser_cargo') ?? ''),
            'ser_dependencia'         => trim($this->request->getPost('ser_dependencia') ?? ''),
            'ser_fecha_ingreso'       => $this->request->getPost('ser_fecha_ingreso') ?: null,
            'ser_fecha_cese'          => $this->request->getPost('ser_fecha_cese') ?: null,
            'ser_numero_legajo'       => $this->request->getPost('ser_numero_legajo') ?: null,
            'ser_estado'              => $this->request->getPost('ser_estado') ?? 'ACTIVO',
            'ser_observaciones'       => $this->request->getPost('ser_observaciones') ?: null,
        ];

        // Procesar foto de perfil si se envió
        $fotoFile = $this->request->getFile('ser_foto_archivo');
        if ($fotoFile && $fotoFile->isValid()) {
            try {
                $fotoSubida = $this->uploadDocument($fotoFile, 'legajos/fotos', ['image/jpeg', 'image/png', 'image/webp'], 4096);
                if ($fotoSubida) {
                    $data['ser_foto'] = $fotoSubida['ruta_relativa'];
                }
            } catch (\Exception $e) {
                return $this->jsonResponse('error', 'Error al subir la fotografía: ' . $e->getMessage(), [], 400);
            }
        }

        $userId = $this->getAuditUserId();

        if (!empty($id)) {
            $data['updated_by'] = $userId;
            if (!$this->servidorModel->update($id, $data)) {
                return $this->jsonResponse('error', 'Error al actualizar el servidor público.', $this->servidorModel->errors(), 422);
            }
            return $this->jsonResponse('success', 'Datos del servidor público actualizados correctamente.', ['id' => $id]);
        } else {
            $data['created_by'] = $userId;
            $nuevoId = $this->servidorModel->insert($data);
            if (!$nuevoId) {
                return $this->jsonResponse('error', 'Error al registrar el servidor público.', $this->servidorModel->errors(), 422);
            }
            return $this->jsonResponse('success', 'Servidor público registrado con éxito.', ['id' => $nuevoId]);
        }
    }

    /**
     * Baja Lógica de un Servidor Público
     */
    public function eliminarServidor(int $id): ResponseInterface
    {
        $servidor = $this->servidorModel->find($id);
        if (!$servidor) {
            return $this->jsonResponse('error', 'Servidor no encontrado.', [], 404);
        }

        $this->servidorModel->update($id, ['deleted_by' => $this->getAuditUserId()]);
        $this->servidorModel->delete($id);

        return $this->jsonResponse('success', 'Servidor dado de baja correctamente.');
    }

    // =========================================================================
    // SECCIÓN 1: DATOS FILIATORIOS Y FAMILIARES
    // =========================================================================
    public function guardarFamiliar(): ResponseInterface
    {
        $famIde = $this->request->getPost('fam_ide');
        $serIde = (int) $this->request->getPost('fam_ser_ide');

        $data = [
            'fam_ser_ide'                => $serIde,
            'fam_parentesco'             => $this->request->getPost('fam_parentesco'),
            'fam_tipo_documento'         => $this->request->getPost('fam_tipo_documento') ?? 'DNI',
            'fam_numero_documento'       => trim($this->request->getPost('fam_numero_documento') ?? ''),
            'fam_nombres'                => trim($this->request->getPost('fam_nombres') ?? ''),
            'fam_apellido_paterno'       => trim($this->request->getPost('fam_apellido_paterno') ?? ''),
            'fam_apellido_materno'       => trim($this->request->getPost('fam_apellido_materno') ?? ''),
            'fam_fecha_nacimiento'       => $this->request->getPost('fam_fecha_nacimiento') ?: null,
            'fam_sexo'                   => $this->request->getPost('fam_sexo') ?? 'M',
            'fam_es_derechohabiente'     => $this->request->getPost('fam_es_derechohabiente') ? 1 : 0,
            'fam_es_contacto_emergencia' => $this->request->getPost('fam_es_contacto_emergencia') ? 1 : 0,
            'fam_telefono'               => $this->request->getPost('fam_telefono') ?: null,
            'fam_direccion'              => $this->request->getPost('fam_direccion') ?: null,
        ];

        // Archivo de sustento (Acta de Nacimiento / Matrimonio)
        $archivo = $this->request->getFile('fam_adjunto');
        if ($archivo && $archivo->isValid()) {
            try {
                $subido = $this->uploadDocument($archivo, 'legajos/seccion1_filiacion');
                if ($subido) {
                    $data['fam_adjunto_sustento'] = $subido['ruta_relativa'];
                    $this->registrarDocumentoEnExpediente($serIde, 1, 'Sustento Familiar - ' . $data['fam_parentesco'], $subido);
                }
            } catch (\Exception $e) {
                return $this->jsonResponse('error', $e->getMessage(), [], 400);
            }
        }

        $userId = $this->getAuditUserId();

        if (!empty($famIde)) {
            $data['updated_by'] = $userId;
            $this->familiarModel->update($famIde, $data);
            return $this->jsonResponse('success', 'Familiar/Derechohabiente actualizado.');
        } else {
            $data['created_by'] = $userId;
            $this->familiarModel->insert($data);
            return $this->jsonResponse('success', 'Familiar/Derechohabiente registrado con éxito.');
        }
    }

    public function eliminarFamiliar(int $id): ResponseInterface
    {
        $this->familiarModel->update($id, ['deleted_by' => $this->getAuditUserId()]);
        $this->familiarModel->delete($id);
        return $this->jsonResponse('success', 'Registro familiar eliminado.');
    }

    // =========================================================================
    // SECCIÓN 2: FORMACIÓN ACADÉMICA
    // =========================================================================
    public function guardarFormacion(): ResponseInterface
    {
        $forIde = $this->request->getPost('for_ide');
        $serIde = (int) $this->request->getPost('for_ser_ide');

        $data = [
            'for_ser_ide'                     => $serIde,
            'for_nivel_educativo'             => $this->request->getPost('for_nivel_educativo'),
            'for_institucion'                 => trim($this->request->getPost('for_institucion') ?? ''),
            'for_carrera_especialidad'        => trim($this->request->getPost('for_carrera_especialidad') ?? ''),
            'for_grado_obtenido'              => trim($this->request->getPost('for_grado_obtenido') ?? ''),
            'for_fecha_expedicion'            => $this->request->getPost('for_fecha_expedicion') ?: null,
            'for_colegio_profesional'         => trim($this->request->getPost('for_colegio_profesional') ?? ''),
            'for_numero_colegiatura'          => trim($this->request->getPost('for_numero_colegiatura') ?? ''),
            'for_es_habilitado'               => $this->request->getPost('for_es_habilitado') ? 1 : 0,
            'for_fecha_habilitacion_vigencia' => $this->request->getPost('for_fecha_habilitacion_vigencia') ?: null,
            'for_pais'                        => $this->request->getPost('for_pais') ?? 'PERÚ',
            'for_registro_sunedu'             => trim($this->request->getPost('for_registro_sunedu') ?? ''),
        ];

        $archivo = $this->request->getFile('for_adjunto');
        if ($archivo && $archivo->isValid()) {
            try {
                $subido = $this->uploadDocument($archivo, 'legajos/seccion2_academico');
                if ($subido) {
                    $data['for_adjunto_sustento'] = $subido['ruta_relativa'];
                    $this->registrarDocumentoEnExpediente($serIde, 2, 'Diploma/Título - ' . $data['for_carrera_especialidad'], $subido);
                }
            } catch (\Exception $e) {
                return $this->jsonResponse('error', $e->getMessage(), [], 400);
            }
        }

        $userId = $this->getAuditUserId();

        if (!empty($forIde)) {
            $data['updated_by'] = $userId;
            $this->formacionModel->update($forIde, $data);
            return $this->jsonResponse('success', 'Registro académico actualizado.');
        } else {
            $data['created_by'] = $userId;
            $this->formacionModel->insert($data);
            return $this->jsonResponse('success', 'Registro académico agregado con éxito.');
        }
    }

    public function eliminarFormacion(int $id): ResponseInterface
    {
        $this->formacionModel->update($id, ['deleted_by' => $this->getAuditUserId()]);
        $this->formacionModel->delete($id);
        return $this->jsonResponse('success', 'Registro académico eliminado.');
    }

    // =========================================================================
    // SECCIÓN 3: EXPERIENCIA LABORAL PREVIA
    // =========================================================================
    public function guardarExperiencia(): ResponseInterface
    {
        $expIde = $this->request->getPost('exp_ide');
        $serIde = (int) $this->request->getPost('exp_ser_ide');

        $data = [
            'exp_ser_ide'               => $serIde,
            'exp_tipo_entidad'          => $this->request->getPost('exp_tipo_entidad') ?? 'PUBLICA',
            'exp_entidad_empresa'       => trim($this->request->getPost('exp_entidad_empresa') ?? ''),
            'exp_cargo_desempenado'     => trim($this->request->getPost('exp_cargo_desempenado') ?? ''),
            'exp_unidad_organica'       => trim($this->request->getPost('exp_unidad_organica') ?? ''),
            'exp_fecha_inicio'          => $this->request->getPost('exp_fecha_inicio'),
            'exp_fecha_fin'             => $this->request->getPost('exp_fecha_fin') ?: null,
            'exp_tiempo_anios'          => (int) $this->request->getPost('exp_tiempo_anios'),
            'exp_tiempo_meses'          => (int) $this->request->getPost('exp_tiempo_meses'),
            'exp_tiempo_dias'           => (int) $this->request->getPost('exp_tiempo_dias'),
            'exp_funciones_principales' => $this->request->getPost('exp_funciones_principales') ?: null,
            'exp_motivo_cese'           => $this->request->getPost('exp_motivo_cese') ?: null,
        ];

        $archivo = $this->request->getFile('exp_adjunto');
        if ($archivo && $archivo->isValid()) {
            try {
                $subido = $this->uploadDocument($archivo, 'legajos/seccion3_experiencia');
                if ($subido) {
                    $data['exp_adjunto_sustento'] = $subido['ruta_relativa'];
                    $this->registrarDocumentoEnExpediente($serIde, 3, 'Certificado Laboral - ' . $data['exp_entidad_empresa'], $subido);
                }
            } catch (\Exception $e) {
                return $this->jsonResponse('error', $e->getMessage(), [], 400);
            }
        }

        $userId = $this->getAuditUserId();

        if (!empty($expIde)) {
            $data['updated_by'] = $userId;
            $this->experienciaModel->update($expIde, $data);
            return $this->jsonResponse('success', 'Experiencia laboral actualizada.');
        } else {
            $data['created_by'] = $userId;
            $this->experienciaModel->insert($data);
            return $this->jsonResponse('success', 'Experiencia laboral agregada correctamente.');
        }
    }

    public function eliminarExperiencia(int $id): ResponseInterface
    {
        $this->experienciaModel->update($id, ['deleted_by' => $this->getAuditUserId()]);
        $this->experienciaModel->delete($id);
        return $this->jsonResponse('success', 'Experiencia laboral eliminada.');
    }

    // =========================================================================
    // SECCIÓN 4: MOVIMIENTOS DE PERSONAL Y DESPLAZAMIENTOS
    // =========================================================================
    public function guardarMovimiento(): ResponseInterface
    {
        $movIde = $this->request->getPost('mov_ide');
        $serIde = (int) $this->request->getPost('mov_ser_ide');

        $data = [
            'mov_ser_ide'                 => $serIde,
            'mov_tipo'                    => $this->request->getPost('mov_tipo'),
            'mov_tipo_documento_sustento' => $this->request->getPost('mov_tipo_documento_sustento') ?? 'RESOLUCION DIRECTORAL',
            'mov_numero_documento'        => trim($this->request->getPost('mov_numero_documento') ?? ''),
            'mov_fecha_documento'         => $this->request->getPost('mov_fecha_documento') ?: null,
            'mov_dependencia_origen'      => trim($this->request->getPost('mov_dependencia_origen') ?? ''),
            'mov_dependencia_destino'     => trim($this->request->getPost('mov_dependencia_destino') ?? ''),
            'mov_cargo_destino'           => trim($this->request->getPost('mov_cargo_destino') ?? ''),
            'mov_fecha_inicio'            => $this->request->getPost('mov_fecha_inicio'),
            'mov_fecha_fin'               => $this->request->getPost('mov_fecha_fin') ?: null,
            'mov_dias_computados'         => (int) $this->request->getPost('mov_dias_computados'),
            'mov_motivo_detalle'          => $this->request->getPost('mov_motivo_detalle') ?: null,
        ];

        $archivo = $this->request->getFile('mov_adjunto');
        if ($archivo && $archivo->isValid()) {
            try {
                $subido = $this->uploadDocument($archivo, 'legajos/seccion4_movimientos');
                if ($subido) {
                    $data['mov_adjunto_sustento'] = $subido['ruta_relativa'];
                    $this->registrarDocumentoEnExpediente($serIde, 4, $data['mov_tipo_documento_sustento'] . ' - ' . $data['mov_numero_documento'], $subido);
                }
            } catch (\Exception $e) {
                return $this->jsonResponse('error', $e->getMessage(), [], 400);
            }
        }

        $userId = $this->getAuditUserId();

        if (!empty($movIde)) {
            $data['updated_by'] = $userId;
            $this->movimientoModel->update($movIde, $data);
            return $this->jsonResponse('success', 'Movimiento de personal actualizado.');
        } else {
            $data['created_by'] = $userId;
            $this->movimientoModel->insert($data);
            return $this->jsonResponse('success', 'Movimiento de personal registrado.');
        }
    }

    public function eliminarMovimiento(int $id): ResponseInterface
    {
        $this->movimientoModel->update($id, ['deleted_by' => $this->getAuditUserId()]);
        $this->movimientoModel->delete($id);
        return $this->jsonResponse('success', 'Movimiento de personal eliminado.');
    }

    // =========================================================================
    // SECCIÓN 5: EVALUACIONES Y CAPACITACIONES
    // =========================================================================
    public function guardarCapacitacion(): ResponseInterface
    {
        $evcIde = $this->request->getPost('evc_ide');
        $serIde = (int) $this->request->getPost('evc_ser_ide');

        $data = [
            'evc_ser_ide'                  => $serIde,
            'evc_tipo'                     => $this->request->getPost('evc_tipo') ?? 'CAPACITACION',
            'evc_titulo'                   => trim($this->request->getPost('evc_titulo') ?? ''),
            'evc_institucion_organizadora' => trim($this->request->getPost('evc_institucion_organizadora') ?? ''),
            'evc_tipo_evento'              => $this->request->getPost('evc_tipo_evento') ?? 'CURSO',
            'evc_fecha_inicio'             => $this->request->getPost('evc_fecha_inicio'),
            'evc_fecha_fin'                => $this->request->getPost('evc_fecha_fin') ?: null,
            'evc_horas_academicas'         => (int) $this->request->getPost('evc_horas_academicas'),
            'evc_creditos'                 => (float) $this->request->getPost('evc_creditos'),
            'evc_calificacion_obtenida'    => trim($this->request->getPost('evc_calificacion_obtenida') ?? ''),
            'evc_es_financiado_entidad'    => $this->request->getPost('evc_es_financiado_entidad') ? 1 : 0,
        ];

        $archivo = $this->request->getFile('evc_adjunto');
        if ($archivo && $archivo->isValid()) {
            try {
                $subido = $this->uploadDocument($archivo, 'legajos/seccion5_capacitaciones');
                if ($subido) {
                    $data['evc_adjunto_sustento'] = $subido['ruta_relativa'];
                    $this->registrarDocumentoEnExpediente($serIde, 5, 'Certificado - ' . $data['evc_titulo'], $subido);
                }
            } catch (\Exception $e) {
                return $this->jsonResponse('error', $e->getMessage(), [], 400);
            }
        }

        $userId = $this->getAuditUserId();

        if (!empty($evcIde)) {
            $data['updated_by'] = $userId;
            $this->capacitacionModel->update($evcIde, $data);
            return $this->jsonResponse('success', 'Capacitación/Evaluación actualizada.');
        } else {
            $data['created_by'] = $userId;
            $this->capacitacionModel->insert($data);
            return $this->jsonResponse('success', 'Capacitación/Evaluación agregada.');
        }
    }

    public function eliminarCapacitacion(int $id): ResponseInterface
    {
        $this->capacitacionModel->update($id, ['deleted_by' => $this->getAuditUserId()]);
        $this->capacitacionModel->delete($id);
        return $this->jsonResponse('success', 'Capacitación/Evaluación eliminada.');
    }

    // =========================================================================
    // SECCIÓN 6: MÉRITOS, RECONOCIMIENTOS Y SANCIONES (PAD)
    // =========================================================================
    public function guardarSancionMerito(): ResponseInterface
    {
        $msaIde = $this->request->getPost('msa_ide');
        $serIde = (int) $this->request->getPost('msa_ser_ide');

        $data = [
            'msa_ser_ide'               => $serIde,
            'msa_tipo'                  => $this->request->getPost('msa_tipo') ?? 'MERITO',
            'msa_subtipo'               => $this->request->getPost('msa_subtipo'),
            'msa_acto_resolutivo'       => trim($this->request->getPost('msa_acto_resolutivo') ?? ''),
            'msa_fecha_acto'            => $this->request->getPost('msa_fecha_acto'),
            'msa_entidad_emisora'       => trim($this->request->getPost('msa_entidad_emisora') ?? ''),
            'msa_numero_expediente_pad' => trim($this->request->getPost('msa_numero_expediente_pad') ?? ''),
            'msa_descripcion_motivo'    => $this->request->getPost('msa_descripcion_motivo') ?: null,
            'msa_periodo_sancion_dias'  => (int) $this->request->getPost('msa_periodo_sancion_dias'),
            'msa_fecha_inicio_efecto'   => $this->request->getPost('msa_fecha_inicio_efecto') ?: null,
            'msa_fecha_fin_efecto'      => $this->request->getPost('msa_fecha_fin_efecto') ?: null,
            'msa_esta_rehabilitado'     => $this->request->getPost('msa_esta_rehabilitado') ? 1 : 0,
        ];

        $archivo = $this->request->getFile('msa_adjunto');
        if ($archivo && $archivo->isValid()) {
            try {
                $subido = $this->uploadDocument($archivo, 'legajos/seccion6_meritos_sanciones');
                if ($subido) {
                    $data['msa_adjunto_sustento'] = $subido['ruta_relativa'];
                    $this->registrarDocumentoEnExpediente($serIde, 6, $data['msa_tipo'] . ' - ' . $data['msa_acto_resolutivo'], $subido);
                }
            } catch (\Exception $e) {
                return $this->jsonResponse('error', $e->getMessage(), [], 400);
            }
        }

        $userId = $this->getAuditUserId();

        if (!empty($msaIde)) {
            $data['updated_by'] = $userId;
            $this->sancionMeritoModel->update($msaIde, $data);
            return $this->jsonResponse('success', 'Registro de mérito/sanción actualizado.');
        } else {
            $data['created_by'] = $userId;
            $this->sancionMeritoModel->insert($data);
            return $this->jsonResponse('success', 'Registro de mérito/sanción guardado con éxito.');
        }
    }

    public function eliminarSancionMerito(int $id): ResponseInterface
    {
        $this->sancionMeritoModel->update($id, ['deleted_by' => $this->getAuditUserId()]);
        $this->sancionMeritoModel->delete($id);
        return $this->jsonResponse('success', 'Registro de mérito/sanción eliminado.');
    }

    // =========================================================================
    // EXPEDIENTE DIGITAL Y DOCUMENTOS DIGITALIZADOS
    // =========================================================================
    public function guardarDocumentoDigital(): ResponseInterface
    {
        $serIde = (int) $this->request->getPost('doc_ser_ide');
        $secIde = (int) $this->request->getPost('doc_sec_ide');
        $titulo = trim($this->request->getPost('doc_titulo') ?? '');

        $archivo = $this->request->getFile('doc_archivo');
        if (!$archivo || !$archivo->isValid()) {
            return $this->jsonResponse('error', 'Debe seleccionar un archivo digital válido (PDF o Imagen).', [], 400);
        }

        try {
            $subido = $this->uploadDocument($archivo, 'legajos/expediente_digital');
            if ($subido) {
                $docId = $this->registrarDocumentoEnExpediente(
                    $serIde,
                    $secIde,
                    $titulo ?: $subido['nombre_original'],
                    $subido,
                    trim($this->request->getPost('doc_numero_folio') ?? ''),
                    $this->request->getPost('doc_fecha_emision') ?: null,
                    $this->request->getPost('doc_observacion') ?: null
                );

                return $this->jsonResponse('success', 'Documento digitalizado incorporado al legajo.', ['doc_ide' => $docId]);
            }
        } catch (\Exception $e) {
            return $this->jsonResponse('error', $e->getMessage(), [], 400);
        }

        return $this->jsonResponse('error', 'No se pudo procesar el archivo.', [], 500);
    }

    public function eliminarDocumentoDigital(int $id): ResponseInterface
    {
        $this->documentoDigitalModel->update($id, ['deleted_by' => $this->getAuditUserId()]);
        $this->documentoDigitalModel->delete($id);
        return $this->jsonResponse('success', 'Documento eliminado del expediente digital.');
    }

    /**
     * Visualización segura de Documentos PDF en el visor
     */
    public function verDocumento(): ResponseInterface
    {
        $pathBase64 = $this->request->getGet('path');
        if (empty($pathBase64)) {
            return $this->response->setStatusCode(400, 'Ruta no proporcionada');
        }

        $pathRelativo = base64_decode(rawurldecode($pathBase64));
        $pathRelativo = str_replace(['..', '\\'], '', $pathRelativo);

        if (!str_starts_with($pathRelativo, 'uploads/legajos/')) {
            return $this->response->setStatusCode(403, 'Acceso no permitido');
        }

        $fullPath = FCPATH . $pathRelativo;
        if (!file_exists($fullPath)) {
            return $this->response->setStatusCode(404, 'Archivo no encontrado');
        }

        $mime = mime_content_type($fullPath) ?: 'application/pdf';

        return $this->response
            ->setHeader('Content-Type', $mime)
            ->setHeader('Content-Disposition', 'inline; filename="' . basename($fullPath) . '"')
            ->setBody(file_get_contents($fullPath));
    }

    /**
     * Descargar documento por ID
     */
    public function descargarDocumento(int $id): ResponseInterface
    {
        $doc = $this->documentoDigitalModel->find($id);
        if (!$doc) {
            return $this->response->setStatusCode(404, 'Documento no encontrado.');
        }

        $fullPath = FCPATH . $doc['doc_ruta_archivo'];
        if (!file_exists($fullPath)) {
            return $this->response->setStatusCode(404, 'El archivo físico no existe en el servidor.');
        }

        return $this->response->download($fullPath, null)->setFileName($doc['doc_nombre_original'] ?: basename($fullPath));
    }

    /**
     * Helper privado para registrar metadatos en leg_documentos_digitales
     */
    private function registrarDocumentoEnExpediente(int $serIde, ?int $secIde, string $titulo, array $archivoData, ?string $folio = null, ?string $fechaEmision = null, ?string $obs = null): int
    {
        return $this->documentoDigitalModel->insert([
            'doc_ser_ide'         => $serIde,
            'doc_sec_ide'         => $secIde,
            'doc_titulo'          => $titulo,
            'doc_numero_folio'    => $folio,
            'doc_fecha_emision'   => $fechaEmision ?: date('Y-m-d'),
            'doc_ruta_archivo'    => $archivoData['ruta_relativa'],
            'doc_nombre_original' => $archivoData['nombre_original'],
            'doc_mime_type'       => $archivoData['mime_type'],
            'doc_peso_kb'         => $archivoData['tamano_kb'],
            'doc_observacion'     => $obs,
            'created_by'          => $this->getAuditUserId(),
        ]);
    }
}

