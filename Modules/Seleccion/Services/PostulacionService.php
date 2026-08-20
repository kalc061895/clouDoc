<?php

namespace Modules\Seleccion\Services;

use Modules\Seleccion\Models\PostulacionModel;
use Modules\Seleccion\Models\ConvocatoriaModel;
use Modules\Seleccion\Models\ConvocatoriaCargoModel;
use Modules\Seleccion\Models\PostulanteModel;
use CodeIgniter\HTTP\IncomingRequest;
class PostulacionService
{
    protected PostulacionModel $postulacionModel;
    protected ConvocatoriaModel $convocatoriaModel;
    protected convocatoriaCargoModel $convocatoriaCargoModel;
    protected PostulanteModel $postulanteModel;

    public function __construct()
    {
        $this->postulacionModel  = new PostulacionModel();
        $this->convocatoriaModel = new ConvocatoriaModel();
        $this->convocatoriaCargoModel = new ConvocatoriaCargoModel();
        $this->postulanteModel   = new PostulanteModel();
    }

    /**
     * Obtiene las postulaciones realizadas por el postulante (pos_ide)
     */
    public function obtenerMisPostulaciones(int $postulanteId): array
    {
        return $this->postulacionModel
            ->select('selec_postulaciones.*, c.con_codigo, c.con_numero, c.con_nombre, c.con_fecha_cierre')
            ->join('selec_convocatoria_cargos cc', 'cc.cco_ide = selec_postulaciones.pto_cco_ide', 'left')
            ->join('selec_convocatorias c', 'c.con_ide = cc.cco_con_ide', 'left')
            ->where('selec_postulaciones.pto_pos_ide', $postulanteId)
            ->orderBy('selec_postulaciones.created_at', 'DESC')
            ->findAll();
    }

    /**
     * Obtiene las convocatorias abiertas vigentes a las que el postulante AÚN no ha postulado
     */
    public function obtenerConvocatoriasDisponibles(int $postulanteId): array
    {
        $fechaActual = date('Y-m-d H:i:s');

        return $this->convocatoriaModel
            ->select('selec_convocatorias.*, t.tco_nombre,e.cet_fecha_inicio,e.cet_fecha_cierre')
            ->where('con_fecha_inicio <=', $fechaActual)
            ->where('con_fecha_cierre >=', $fechaActual)
            ->where("con_ide NOT IN (
                SELECT cc.cco_con_ide 
                from selec_postulaciones p 
                JOIN selec_convocatoria_cargos cc ON cc.cco_ide = p.pto_cco_ide 
                WHERE p.pto_pos_ide = {$postulanteId}
            )", null, false)
            ->join('selec_tipos_convocatoria t', 't.tco_ide = selec_convocatorias.con_tco_ide', 'left')
            ->join('selec_convocatoria_etapas e', 'e.cet_con_ide = selec_convocatorias.con_ide AND e.cet_eta_ide = 2', 'left')
            ->findAll();
    }

    /**
     * Procesa y registra la postulación con sus declaraciones juradas y subida de archivos PDF
     */
    public function guardarPostulacion(IncomingRequest $request, int $postulanteId): array
    {
        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $convocatoriaId = (int)$request->getPost('pto_con_ide');
            $cargoId        = (int)$request->getPost('pto_cco_ide');

            // 1. Validar existencia del cargo/plaza
            $cargo = $this->convocatoriaCargoModel->find($cargoId);
            if (!$cargo) {
                throw new \Exception("La plaza seleccionada no es válida o no existe.");
            }

            // 2. Verificar que el postulante no haya registrado previa postulación a esta convocatoria
            $yaPostulo = $this->postulacionModel
                ->join('selec_convocatoria_cargos cc', 'cc.cco_ide = selec_postulaciones.pto_cco_ide')
                ->where('selec_postulaciones.pto_pos_ide', $postulanteId)
                ->where('cc.cco_con_ide', $convocatoriaId)
                ->first();

            if ($yaPostulo) {
                throw new \Exception("Ya cuenta con un registro presentado para esta convocatoria.");
            }

            // 3. Generar código correlativo de expediente (Ej: EXP-2026-0001)
            $anioActual = date('Y');
            $correlativo = $this->postulacionModel->where("YEAR(created_at)", $anioActual)->countAllResults() + 1;
            $codigoExpediente = sprintf("EXP-%s-%04d", $anioActual, $correlativo);

            // 4. Armar datos de la postulación
            $dataPostulacion = [
                'pto_pos_ide'            => $postulanteId,
                'pto_cco_ide'            => $cargoId,
                'pto_codigo'             => $codigoExpediente,
                'pto_fecha_presentacion' => date('Y-m-d H:i:s'),
                'pto_confirmado'         => 1, // Presentado
                'pto_dj_nepotismo'       => (int)$request->getPost('dj_nepotismo'),
                'pto_dj_antecedentes'    => (int)$request->getPost('dj_antecedentes'),
                'pto_dj_deudores'        => (int)$request->getPost('dj_deudores'),
                'pto_dj_veracidad'       => (int)$request->getPost('dj_veracidad'),
                'created_at'             => date('Y-m-d H:i:s'),
            ];

            $postulacionId = $this->postulacionModel->insert($dataPostulacion, true);

            if (!$postulacionId) {
                throw new \Exception("No se pudo registrar la postulación en la base de datos.");
            }

            // 5. Procesar la subida de los archivos PDF adjuntos
            $this->procesarArchivosPdf($request, $postulacionId);

            $db->transCommit();

            return [
                'status'   => 'success',
                'message'  => "Su postulación ha sido registrada con exito. Expediente N°: {$codigoExpediente}",
                'redirect' => base_url('seleccion/postulacion')
            ];
        } catch (\Exception $e) {
            $db->transRollback();
            return [
                'status'  => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Almacena en disco los PDFs subidos y registra la referencia en la BD
     */
    protected function procesarArchivosPdf(IncomingRequest $request, int $postulacionId): void
    {
        $db = \Config\Database::connect();
        $uploadPath = WRITEPATH . 'uploads/expedientes/' . date('Y/m/');

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // A) Caso 1: Array de anexos obligatorios por bases (anexos_pdf[cod_ide])
        $filesAnexos = $request->getFiles();

        if (isset($filesAnexos['anexos_pdf']) && is_array($filesAnexos['anexos_pdf'])) {
            foreach ($filesAnexos['anexos_pdf'] as $documentoId => $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = "POST_{$postulacionId}_DOC_{$documentoId}_" . $file->getRandomName();
                    $file->move($uploadPath, $newName);

                    $db->table('selec_postulacion_anexos')->insert([
                        'pan_pto_ide' => $postulacionId,
                        'pan_cod_ide' => $documentoId,
                        'pan_archivo' => 'uploads/expedientes/' . date('Y/m/') . $newName,
                        'created_at'  => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }

        // B) Caso 2: Subida por campos independientes estándar (DNI, CV, Anexos)
        $camposEstandar = ['pdf_dni' => 'DNI', 'pdf_cv' => 'CV Documentado', 'pdf_anexos' => 'Anexos Firmados'];

        foreach ($camposEstandar as $campoKey => $tipoNombre) {
            $file = $request->getFile($campoKey);
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = "POST_{$postulacionId}_{$campoKey}_" . $file->getRandomName();
                $file->move($uploadPath, $newName);

                $db->table('selec_postulacion_anexos')->insert([
                    'pan_pto_ide' => $postulacionId,
                    'pan_cod_ide' => null,
                    'pan_nombre'  => $tipoNombre,
                    'pan_archivo' => 'uploads/expedientes/' . date('Y/m/') . $newName,
                    'created_at'  => date('Y-m-d H:i:s')
                ]);
            }
        }
    }
}
