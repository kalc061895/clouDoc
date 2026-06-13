<?php

namespace App\Services\Contratacion;

use App\Models\Contratacion\ExtraModel;
use App\Models\Contratacion\AnexoModel;
use App\Models\Contratacion\PostulanteModel;
class InformacionExtraService
{

    public function listar($userId)
    {

        $anexoModel = new AnexoModel();
        $postulanteModel = new PostulanteModel();
        $extraModel = new ExtraModel();

        $postulanteId = $this->getPostulanteId();
        
        return $extraModel
            ->select('informacion_extra.*, ruta as archivo')
            ->join('anexos', 'anexos.id_anexo = informacion_extra.id_anexo', 'left')
            ->where('id_postulante', $postulanteId)
            ->findAll();
    }

    public function guardar($userId, $request)
    {
        $anexoModel = new AnexoModel();
        $postulanteModel = new PostulanteModel();
        $extraModel = new ExtraModel();

        $postulanteId = $this->getPostulanteId();

        if ($file = $request->getFile('archivo')) {
            if ($file && $file->isValid() && ! $file->hasMoved()) {
                $nombre = $file->getRandomName();

                //$file->move(WRITEPATH . 'uploads/anexos/extra', $nombre);
                $file->move('uploads/anexos/extra', $nombre);

                $idAnexo = $anexoModel->insert([
                    'tipo'             => 'EXTRA',
                    'nombre_original'  => $file->getClientName(),
                    'ruta'             => 'uploads/anexos/extra/' . $nombre,
                    'mime'             => $file->getClientMimeType(),
                    'extension'        => $file->getClientExtension(),
                    'size'             => $file->getSize(),
                ]);
            } else {
                /*// AQUÍ ESTÁ EL TRUCO:
                // getErrorString() te dará el motivo real (ej: "The file exceeds the upload_max_filesize directive in php.ini")
                // getError() te dará el código numérico de error de PHP
                $errorMsg = $file->getErrorString();
                $errorCode = $file->getError();

                throw new \Exception("Error de validación: $errorMsg (Código: $errorCode)");
                */
                return ['status' => 'error', 'message' => 'Error al subir el archivo Max. 10MB'];
            }
        } else {
            return ['status' => 'error', 'message' => 'Error al subir el archivo Max. 10MB'];
        }

        $idFormacion = $extraModel->insert(
            [
                'id_postulante' => $postulanteId,
                'tipo' => $request->getPost('tipo'),
                'descripcion' => $request->getPost('descripcion'),
                'fecha_expedicion' => $request->getPost('fecha_expedicion'),
                'id_anexo' => $idAnexo ?? null,
            ]
        );
        return ['status' => 'success'];
    }

    public function eliminar($userId, $idExtra)
    {
        $anexoModel = new AnexoModel();
        $postulanteModel = new PostulanteModel();
        $extraModel = new ExtraModel();


        $postulanteId = $this->getPostulanteId();

        $extra = $extraModel
            ->where('id_extra', $idExtra)
            ->where('id_postulante', $postulanteId)
            ->first();

        if (!$extra) {
            return [
                'status' => 'error',
                'message' => 'Registro no encontrado'
            ];
        }

        // eliminar anexos asociados
        $anexos = $anexoModel->where('id_anexo', $extra['id_anexo'])->findAll();
        foreach ($anexos as $a) {
            if (is_file('' . $a['ruta'])) {
                unlink('' . $a['ruta']);
            }
            /*if (is_file(WRITEPATH . 'uploads/' . $a['ruta'])) {
                unlink(WRITEPATH . 'uploads/' . $a['ruta']);
            }*/
        }

        $anexoModel->where('id_anexo', $extra['id_anexo'])->delete();
        $extraModel->delete($idExtra);

        return ['status' => 'success'];
    }

    protected function getPostulanteId()
    {
        $userId = auth()->id();
        $postulanteModel = new PostulanteModel();
        $postulante = $postulanteModel
            ->where('user_id', $userId)
            ->first();

        if (!$postulante) {
            throw new \Exception('Postulante no encontrado');
        }

        return $postulante['id_postulante'];
    }
}
