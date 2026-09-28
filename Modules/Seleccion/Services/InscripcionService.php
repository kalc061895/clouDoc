<?php

namespace Modules\Seleccion\Services;

use CodeIgniter\HTTP\Files\UploadedFile;
use Modules\Seleccion\Models\{AnexoModel, ConvocatoriaCargoModel, EstadoPostulacionModel, ExpedienteDocumentoModel, PostulacionAnexoModel, PostulacionDeclaracionModel, PostulacionModel, PostulanteCapacitacionModel, PostulanteExperienciaModel, PostulanteFormacionModel, PostulanteModel, PostulanteProfesionModel, PostulantesOtroModel, TipoDeclaracionModel, ValidacionPostulacionModel, ConvocatoriaModel};

/** Servicio de dominio para la ficha de inscripción. Ningún controlador decide el estado de una postulación. */
class InscripcionService
{
    private $db;
    private PostulanteModel $postulantes;
    private PostulacionModel $postulaciones;

    public function __construct()
    {
        $this->db = db_connect();
        $this->postulantes = new PostulanteModel();
        $this->postulaciones = new PostulacionModel();
    }

    public function postulanteActual(int $userId): ?array
    {
        return $this->postulantes->where('pos_user_id', $userId)->first();
    }

    public function postulacionActual(int $userId, int $convocatoriaId): ?array
    {
        return $this->postulaciones->select('selec_postulaciones.*, cc.cco_con_ide, e.epo_codigo, e.epo_nombre')
            ->join('selec_convocatoria_cargos cc', 'cc.cco_ide = selec_postulaciones.pto_cco_ide')
            ->join('selec_estados_postulacion e', 'e.epo_ide = selec_postulaciones.pto_epo_ide')
            ->join('selec_postulantes p', 'p.pos_ide = selec_postulaciones.pto_pos_ide')
            ->where('p.pos_user_id', $userId)->where('cc.cco_con_ide', $convocatoriaId)->first();
    }

    public function assertEditable(array $postulacion): void
    {
        if ((bool) $postulacion['pto_confirmado'] || strtoupper((string) $postulacion['epo_codigo']) === 'PRESENTADO') {
            
            //throw new \DomainException('El expediente ya fue presentado y no puede modificarse.');
        }
    }

    public function seleccionarPlaza(int $userId, int $convocatoriaId, int $cargoId): array
    {
        $postulante = $this->postulanteActual($userId);
        if (!$postulante)
            throw new \DomainException('Primero complete sus datos personales para crear la postulación.');
        $cargo = (new ConvocatoriaCargoModel())->where('cco_ide', $cargoId)->where('cco_con_ide', $convocatoriaId)->first();
        if (!$cargo)
            throw new \DomainException('La plaza no pertenece a la convocatoria seleccionada.');
        $actual = $this->postulacionActual($userId, $convocatoriaId);
        if ($actual) {
            $this->assertEditable($actual);
            if ((int) $actual['pto_cco_ide'] === $cargoId)
                return $actual;
            $this->postulaciones->update($actual['pto_ide'], ['pto_cco_ide' => $cargoId]);
            return $this->postulacionActual($userId, $convocatoriaId);
        }
        $estado = (new EstadoPostulacionModel())->where('epo_codigo', 'REGISTRANDO')->first();
        if (!$estado)
            throw new \DomainException('No está configurado el estado REGISTRANDO.');
        $this->db->transBegin();
        try {
            $conv = (new ConvocatoriaModel())->find($convocatoriaId);
            // El índice único de pto_codigo es la barrera final ante concurrencia.
            $codigo = 'C-' . $conv['con_ide'] . '-' . $conv['con_codigo'] . '-' . $conv['con_anio'] . '-' . strtoupper(bin2hex(random_bytes(4)));
            $id = $this->postulaciones->insert(['pto_codigo' => $codigo, 'pto_pos_ide' => $postulante['pos_ide'], 'pto_cco_ide' => $cargoId, 'pto_epo_ide' => $estado['epo_ide'], 'pto_confirmado' => false], true);
            if (!$id || !$this->db->transStatus())
                throw new \RuntimeException('No se pudo crear la postulación.');
            $this->db->transCommit();
            return $this->postulacionActual($userId, $convocatoriaId);
        } catch (\Throwable $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    public function guardarDatosPersonales(int $userId, int $convocatoriaId, array $data): int
    {
        $actual = $this->postulacionActual($userId, $convocatoriaId);
        if ($actual)
            $this->assertEditable($actual);
        foreach (['pos_tdo_ide', 'pos_documento', 'pos_nombres', 'pos_apellido_paterno', 'pos_email'] as $campo) {
            if (empty($data[$campo]))
                throw new \DomainException('Complete todos los datos personales obligatorios.');
        }
        if (!filter_var($data['pos_email'], FILTER_VALIDATE_EMAIL))
            throw new \DomainException('El correo electrónico no es válido.');
        $permitidos = ['pos_tdo_ide', 'pos_documento', 'pos_nombres', 'pos_apellido_paterno', 'pos_apellido_materno', 'pos_fecha_nacimiento', 'pos_sexo', 'pos_direccion', 'pos_dep_ide', 'pos_prv_ide', 'pos_dis_ide', 'pos_telefono', 'pos_email'];
        $guardar = array_intersect_key($data, array_flip($permitidos));
        $postulante = $this->postulanteActual($userId);
        if ($postulante) {
            $this->postulantes->update($postulante['pos_ide'], $guardar);
            return (int) $postulante['pos_ide'];
        }
        $guardar['pos_user_id'] = $userId;
        return (int) $this->postulantes->insert($guardar, true);
    }

    public function guardarRegistro(int $userId, int $convocatoriaId, string $tipo, array $data, ?UploadedFile $archivo = null): int
    {
        $post = $this->requerirEditable($userId, $convocatoriaId);
        $mapa = [
            'profesional' => [
                PostulanteProfesionModel::class,
                'ppr_ide',
                'ppr_pos_ide',
                ['ppr_pro_ide', 'ppr_institucion', 'ppr_grado', 'ppr_titulo', 'ppr_fecha', 'ppr_colegiatura', 'ppr_habilitacion'],
                'ppr_documento_ide'
            ],
            'academica' => [
                PostulanteFormacionModel::class,
                'pfo_ide',
                'pfo_pos_ide',
                ['pfo_nfo_ide', 'pfo_institucion', 'pfo_carrera', 'pfo_grado', 'pfo_fecha_inicio', 'pfo_fecha_culminacion', 'pfo_fecha_obtencion'],
                'pfo_documento_ide'
            ],
            'otros' => [
                PostulantesOtroModel::class,
                'otr_ide',
                'otr_pos_ide',
                [
                    'otr_tipo',
                    'otr_nombre',
                    'otr_institucion',
                    'otr_descripcion',
                    'otr_fecha_expedicion',
                    'otr_fecha_inicio',
                    'otr_fecha_fin',
                    'otr_folios',
                ],
                'otr_documento_ide'
            ],
            'experiencia' => [
                PostulanteExperienciaModel::class,
                'pex_ide',
                'pex_pos_ide',
                ['pex_institucion', 'pex_cargo', 'pex_area', 'pex_mvi_ide', 'pex_fecha_inicio', 'pex_fecha_termino', 'pex_descripcion'],
                'pex_documento_ide'
            ],
            'capacitaciones' => [
                PostulanteCapacitacionModel::class,
                'pca_ide',
                'pca_pos_ide',
                ['pca_nombre', 'pca_institucion', 'pca_tipo', 'pca_fecha', 'pca_horas', 'pca_modalidad'],
                'pca_documento_ide'
            ],
        ];
        if (!isset($mapa[$tipo]))
            throw new \InvalidArgumentException('Tipo de registro inválido.');
        [$clase, $pk, $fk, $campos, $campoDocumento] = $mapa[$tipo];
        $model = new $clase();
        $registroId = (int) ($data[$pk] ?? 0);
        if ($registroId && !$model->where($pk, $registroId)->where($fk, $post['pto_pos_ide'])->first())
            throw new \DomainException('Registro no autorizado.');
        $guardar = array_intersect_key($data, array_flip($campos));
        $guardar[$fk] = $post['pto_pos_ide'];
        if ($tipo === 'experiencia') {
            $inicio = new \DateTimeImmutable((string) ($guardar['pex_fecha_inicio'] ?? ''));
            $fin = new \DateTimeImmutable((string) ($guardar['pex_fecha_termino'] ?? ''));
            if ($fin < $inicio)
                throw new \DomainException('La fecha de término no puede ser anterior a la fecha de inicio.');
            $guardar['pex_dias_declarados'] = $inicio->diff($fin)->days + 1;
        }
        if ($tipo === 'capacitaciones' && (!isset($guardar['pca_horas']) || (float) $guardar['pca_horas'] <= 0))
            throw new \DomainException('Las horas deben ser mayores que cero.');
        $this->db->transBegin();
        try {
            if ($registroId) {
                $model->update($registroId, $guardar);
            } else {
                $registroId = (int) $model->insert($guardar, true);
            }
            if ($archivo && $archivo->isValid() && !$archivo->hasMoved()) {
                $documento = $this->guardarDocumento($post, $archivo, strtoupper($tipo));
                $model->update($registroId, [$campoDocumento => $documento]);
            }
            if (!$this->db->transStatus())
                throw new \RuntimeException('No se pudo guardar el registro.');
            $this->db->transCommit();
            return $registroId;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    public function eliminarRegistro(int $userId, int $convocatoriaId, string $tipo, int $id): void
    {
        $post = $this->requerirEditable($userId, $convocatoriaId);
        $mapa = [
            'profesional' => [PostulanteProfesionModel::class, 'ppr_ide', 'ppr_pos_ide'],
            'academica' => [PostulanteFormacionModel::class, 'pfo_ide', 'pfo_pos_ide'],
            'otros' => [PostulantesOtroModel::class, 'otr_ide', 'otr_pos_ide'],
            'experiencia' => [PostulanteExperienciaModel::class, 'pex_ide', 'pex_pos_ide'],
            'capacitaciones' => [PostulanteCapacitacionModel::class, 'pca_ide', 'pca_pos_ide']
        ];
        if (!isset($mapa[$tipo]))
            throw new \InvalidArgumentException('Tipo inválido.');
        [$clase, $pk, $fk] = $mapa[$tipo];
        $model = new $clase();
        if (!$model->where($pk, $id)->where($fk, $post['pto_pos_ide'])->first())
            throw new \DomainException('Registro no autorizado.');
        $model->delete($id);
    }

    public function guardarAnexo(int $userId, int $convocatoriaId, int $anexoId, UploadedFile $archivo): void
    {
        $post = $this->requerirEditable($userId, $convocatoriaId);
        $anexo = (new AnexoModel())->where('ane_ide', $anexoId)->where('ane_con_ide', $convocatoriaId)->first();
        if (!$anexo)
            throw new \DomainException('El anexo no pertenece a esta convocatoria.');
        $doc = $this->guardarDocumento($post, $archivo, 'ANEXO_' . $anexoId);
        $model = new PostulacionAnexoModel();
        $existente = $model->where('pan_pto_ide', $post['pto_ide'])->where('pan_ane_ide', $anexoId)->first();
        $data = ['pan_pto_ide' => $post['pto_ide'], 'pan_ane_ide' => $anexoId, 'pan_exd_ide' => $doc, 'pan_presentado' => true];
        $existente ? $model->update($existente['pan_ide'], $data) : $model->insert($data);
    }

    public function aceptarDeclaracion(int $userId, int $convocatoriaId, int $declaracionId, bool $acepta): void
    {
        $post = $this->requerirEditable($userId, $convocatoriaId);
        $tipo = (new TipoDeclaracionModel())->where('tde_ide', $declaracionId)->where('tde_estado', 'ACTIVO')->first();
        if (!$tipo)
            throw new \DomainException('La declaración no está disponible.');
        $model = new PostulacionDeclaracionModel();
        $existente = $model->where('pde_pto_ide', $post['pto_ide'])->where('pde_tde_ide', $declaracionId)->first();
        $data = ['pde_pto_ide' => $post['pto_ide'], 'pde_tde_ide' => $declaracionId, 'pde_acepta' => $acepta, 'pde_fecha' => date('Y-m-d H:i:s'), 'pde_ip' => service('request')->getIPAddress(), 'pde_hash' => hash('sha256', $post['pto_ide'] . '|' . $declaracionId . '|' . $acepta . '|' . date('c'))];
        $existente ? $model->update($existente['pde_ide'], $data) : $model->insert($data);
    }

    public function validar(int $userId, int $convocatoriaId): array
    {
        $post = $this->requerirEditable($userId, $convocatoriaId);
        $pos = $this->postulantes->find($post['pto_pos_ide']);
        $checks = [
            [
                'PLAZA',
                'Plaza seleccionada',
                !empty($post['pto_cco_ide']),
                'Seleccione una plaza.'
            ],
            [
                'DATOS',
                'Datos personales completos',
                !empty($pos['pos_documento']) && !empty($pos['pos_nombres']) && !empty($pos['pos_apellido_paterno']) && !empty($pos['pos_email']),
                'Complete los datos personales obligatorios.'
            ],
            [
                'DECLARACIONES',
                'Declaraciones juradas',
                $this->declaracionesCompletas($post['pto_ide']),
                'Acepte todas las declaraciones activas.'
            ],
            [
                'ANEXOS',
                'Anexos obligatorios',
                $this->anexosCompletos($post['pto_ide'], $convocatoriaId),
                'Faltan anexos obligatorios.'
            ],
        ];

        // Requisitos configurados para la plaza. Estas comprobaciones validan la declaración,
        // no asignan puntaje ni sustituyen la revisión posterior de la comisión.

        $requisitos = $this->db->table('selec_requisitos')->where('req_cco_ide', $post['pto_cco_ide'])->where('req_obligatorio', true)->get()->getResultArray();

        $profesiones = (new PostulanteProfesionModel())->where('ppr_pos_ide', $post['pto_pos_ide'])->findAll();

        $formaciones = (new PostulanteFormacionModel())->where('pfo_pos_ide', $post['pto_pos_ide'])->findAll();

        $diasExperiencia = (int) ($this->db->table('selec_postulante_experiencias')->selectSum('pex_dias_declarados')->where('pex_pos_ide', $post['pto_pos_ide'])->get()->getRow()->pex_dias_declarados ?? 0);

        foreach ($requisitos as $req) {
            $formacion = $this->db->table('selec_requisito_formacion')->where('rfo_req_ide', $req['req_ide'])->get()->getResultArray();

            $experiencia = $this->db->table('selec_requisito_experiencia')->where('rex_req_ide', $req['req_ide'])->get()->getResultArray();

            $cumple = true;
            $detalle = '';

            foreach ($formacion as $f) {
                $coincide = false;
                foreach (array_merge($profesiones, $formaciones) as $r) {
                    if ((!$f['rfo_pro_ide'] || ($r['ppr_pro_ide'] ?? null) == $f['rfo_pro_ide']) && (!$f['rfo_nfo_ide'] || ($r['pfo_nfo_ide'] ?? null) == $f['rfo_nfo_ide']) && (!$f['rfo_grado'] || strcasecmp((string) ($r['ppr_grado'] ?? $r['pfo_grado'] ?? ''), (string) $f['rfo_grado']) === 0)) {
                        $coincide = true;
                        break;
                    }
                }
                if (!$coincide) {
                    $cumple = false;
                    $detalle = 'No se declaró la formación requerida.';
                    break;
                }
            }
            foreach ($experiencia as $e) {
                $requerido = (int) $e['rex_anios'] * 365 + (int) $e['rex_meses'] * 30 + (int) $e['rex_dias'];
                if ($diasExperiencia < $requerido) {
                    $cumple = false;
                    $detalle = "Se requiere un mínimo declarado de {$requerido} días; registra {$diasExperiencia}.";
                    break;
                }
            }
            if ($formacion || $experiencia)
                $checks[] = ['REQ-' . $req['req_ide'], $req['req_nombre'], $cumple, $detalle ?: 'No cumple el requisito declarado.'];
        }

        $model = new ValidacionPostulacionModel();

        $this->db->table('selec_validaciones_postulacion')->where('vpo_pto_ide', $post['pto_ide'])->delete();

        foreach ($checks as [$codigo, $nombre, $resultado, $observacion])
            $model->insert([
                'vpo_pto_ide' => $post['pto_ide'],
                'vpo_codigo' => $codigo,
                'vpo_nombre' => $nombre,
                'vpo_resultado' => $resultado,
                'vpo_observacion' => $resultado ? null : $observacion,
                'vpo_fecha' => date('Y-m-d H:i:s')
            ]);

        return $model->where('vpo_pto_ide', $post['pto_ide'])->findAll();
    }

    public function confirmar(int $userId, int $convocatoriaId): array
    {
        $post = $this->requerirEditable($userId, $convocatoriaId);
        $this->db->transBegin();
        try {
            $validaciones = $this->validar($userId, $convocatoriaId);
            foreach ($validaciones as $v)
                if (!(bool) $v['vpo_resultado'])
                    throw new \DomainException('Aún existen validaciones pendientes.');
            $estado = (new EstadoPostulacionModel())->where('epo_codigo', 'PRESENTADO')->first();
            if (!$estado)
                throw new \DomainException('No está configurado el estado PRESENTADO.');
            $hash = $this->hashExpediente($post['pto_ide']);
            $this->postulaciones->update($post['pto_ide'], ['pto_epo_ide' => $estado['epo_ide'], 'pto_confirmado' => true, 'pto_fecha_presentacion' => date('Y-m-d H:i:s'), 'pto_ip' => service('request')->getIPAddress(), 'pto_hash_expediente' => $hash]);
            if (!$this->db->transStatus())
                throw new \RuntimeException('No se pudo confirmar.');
            $this->db->transCommit();
            return $this->postulaciones->find($post['pto_ide']);
        } catch (\Throwable $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    private function requerirEditable(int $userId, int $convocatoriaId): array
    {
        $p = $this->postulacionActual($userId, $convocatoriaId);
        if (!$p)
            throw new \DomainException('Seleccione una plaza antes de continuar.');

        $this->assertEditable($p);
        return $p;
    }
    private function guardarDocumento(array $post, UploadedFile $archivo, string $tipo): int
    {
        if (!$archivo->isValid() || $archivo->hasMoved() || $archivo->getSize() > 10 * 1024 * 1024)
            throw new \DomainException('El archivo no es válido o excede 10 MB.');
        $mime = $archivo->getMimeType();
        if (!in_array($mime, ['application/pdf', 'image/jpeg', 'image/png'], true))
            throw new \DomainException('Solo se permiten PDF, JPG o PNG.');
        $ruta = WRITEPATH . 'uploads/expedientes/' . date('Y/m');
        if (!is_dir($ruta) && !mkdir($ruta, 0755, true) && !is_dir($ruta))
            throw new \RuntimeException('No se pudo preparar el directorio de documentos.');
        $nombre = $archivo->getRandomName();
        $archivo->move($ruta, $nombre);
        $archivoFisico = $ruta . DIRECTORY_SEPARATOR . $nombre;
        return (int) (new ExpedienteDocumentoModel())->insert(['exd_pto_ide' => $post['pto_ide'], 'exd_tipo_documento' => $tipo, 'exd_nombre_original' => $archivo->getClientName(), 'exd_nombre_interno' => $nombre, 'exd_ruta' => 'uploads/expedientes/' . date('Y/m/') . $nombre, 'exd_mime' => $mime, 'exd_tamanio' => filesize($archivoFisico), 'exd_hash' => hash_file('sha256', $archivoFisico), 'exd_version' => 1, 'exd_estado' => 'ACTIVO', 'exd_fecha_carga' => date('Y-m-d H:i:s')], true);
    }
    private function anexosCompletos(int $pto, int $con): bool
    {
        $total = (new AnexoModel())->where('ane_con_ide', $con)->where('ane_estado', 'ACTIVO')->where('ane_obligatorio', true)->countAllResults();
        if (!$total)
            return true;
        return $this->db->table('selec_postulacion_anexos pa')->join('selec_anexos a', 'a.ane_ide=pa.pan_ane_ide')->where('pa.pan_pto_ide', $pto)->where('pa.pan_presentado', true)->where('a.ane_obligatorio', true)->countAllResults() === $total;
    }
    private function declaracionesCompletas(int $pto): bool
    {
        $total = (new TipoDeclaracionModel())->where('tde_estado', 'ACTIVO')->countAllResults();
        if (!$total)
            return true;
        return (new PostulacionDeclaracionModel())->where('pde_pto_ide', $pto)->where('pde_acepta', true)->countAllResults() === $total;
    }
    private function hashExpediente(int $pto): string
    {
        $docs = (new ExpedienteDocumentoModel())->where('exd_pto_ide', $pto)->orderBy('exd_ide')->findAll();
        return hash('sha256', json_encode(['postulacion' => $this->postulaciones->find($pto), 'documentos' => array_column($docs, 'exd_hash')]));
    }
}
