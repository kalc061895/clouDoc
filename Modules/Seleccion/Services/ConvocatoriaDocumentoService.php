<?php

namespace Modules\Seleccion\Services;

use Modules\Seleccion\Models\ConvocatoriaDocumentoModel;
use CodeIgniter\HTTP\Files\UploadedFile;
use Throwable;

class ConvocatoriaDocumentoService
{
    protected ConvocatoriaDocumentoModel $model;
    protected string $uploadPath;

    public function __construct()
    {
        $this->model = new ConvocatoriaDocumentoModel();
        $this->uploadPath = WRITEPATH . 'uploads/convocatorias/documentos/';
    }

    public function obtenerPorConvocatoria(int $convocatoriaId): array
    {
        return $this->model->getDocumentosPorConvocatoria($convocatoriaId);
    }

    /**
     * Guarda o actualiza un documento requerido/anexo con o sin archivo adjunto.
     */
    public function guardar(array $data, ?UploadedFile $file = null): array
    {
        // Limpiar strings vacíos
        foreach ($data as $key => $value) {
            if ($value === '') {
                $data[$key] = null;
            }
        }

        $rules = [
            'cod_con_ide'     => 'required|is_natural_no_zero',
            'cod_nombre'      => 'required|min_length[3]|max_length[255]',
            'cod_tipo'        => 'required|in_list[BASES,ANEXO,REQUISITO,DECLARACION_JURADA,OTRO]',
            'cod_obligatorio' => 'required|in_list[0,1]',
            'cod_descripcion' => 'permit_empty',
        ];

        $validation = \Config\Services::validation();
        $validation->setRules($rules);

        if (!$validation->run($data)) {
            return [
                'ok'      => false,
                'code'    => 400,
                'message' => 'Datos no válidos.',
                'errors'  => $validation->getErrors()
            ];
        }

        $id = !empty($data['cod_ide']) ? (int) $data['cod_ide'] : null;

        // Procesar archivo si se subió uno nuevo
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $maxSize = 10 * 1024 * 1024; // 10MB
            if ($file->getSize() > $maxSize) {
                return ['ok' => false, 'code' => 400, 'message' => 'El archivo supera el límite permitido de 10MB.'];
            }

            $allowedMimes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            if (!in_array($file->getMimeType(), $allowedMimes)) {
                return ['ok' => false, 'code' => 400, 'message' => 'Formato no permitido. Solo se aceptan archivos PDF o Word.'];
            }

            // Crear directorio por convocatoria
            $folder = $this->uploadPath . $data['cod_con_ide'] . '/';
            if (!is_dir($folder)) {
                mkdir($folder, 0755, true);
            }

            $newName = $file->getRandomName();
            $file->move($folder, $newName);

            $filePath = $folder . $newName;

            $data['cod_nombre_interno'] = $newName;
            $data['cod_ruta']           = 'uploads/convocatorias/documentos/' . $data['cod_con_ide'] . '/' . $newName;
            $data['cod_mime']           = $file->getClientMimeType();
            $data['cod_tamanio']        = $file->getSize();
            $data['cod_hash']           = file_exists($filePath) ? md5_file($filePath) : null;
        }

        try {
            if ($id) {
                $this->model->update($id, $data);
                return [
                    'ok'      => true,
                    'code'    => 200,
                    'message' => 'Documento actualizado correctamente.',
                    'data'    => $this->model->find($id)
                ];
            } else {
                $data['cod_version'] = 1;
                $nuevoId = $this->model->insert($data, true);
                return [
                    'ok'      => true,
                    'code'    => 201,
                    'message' => 'Documento o anexo registrado con éxito.',
                    'data'    => $this->model->find($nuevoId)
                ];
            }
        } catch (Throwable $e) {
            log_message('error', '[Seleccion] Error al guardar documento: {message}', ['message' => $e->getMessage()]);
            return ['ok' => false, 'code' => 500, 'message' => 'Error interno al procesar el documento.'];
        }
    }

    public function eliminar(int $id): array
    {
        try {
            $doc = $this->model->find($id);
            if (!$doc) {
                return ['ok' => false, 'code' => 404, 'message' => 'El documento no existe.'];
            }

            $this->model->delete($id);
            return ['ok' => true, 'code' => 200, 'message' => 'Documento eliminado correctamente.'];
        } catch (Throwable $e) {
            log_message('error', '[Seleccion] Error al eliminar documento: {message}', ['message' => $e->getMessage()]);
            return ['ok' => false, 'code' => 500, 'message' => 'Error al intentar eliminar el documento.'];
        }
    }
}
