<?php

namespace App\Services\Contratacion;

use App\Models\Contratacion\FormacionModel;
use App\Models\Contratacion\AnexoModel;
use App\Models\Contratacion\PostulanteModel;


class FormacionService
{

    public function listar($userId)
    {
        $anexoModel = new AnexoModel();
        $postulanteModel = new PostulanteModel();
        $formacionModel = new FormacionModel();

        $postulanteId = $this->getPostulanteId();

        return $formacionModel
            ->select('formacion_profesional.*, ruta as archivo')
            ->join('anexos', 'anexos.id_anexo = formacion_profesional.id_anexo', 'left')
            ->where('id_postulante', $postulanteId)
            ->findAll();
    }
    public function guardar($userId, $request)
    {
        $anexoModel = new AnexoModel();
        $postulanteModel = new PostulanteModel();
        $formacionModel = new FormacionModel();

        $postulanteId = $this->getPostulanteId();
        if ($file = $request->getFile('archivo')) {
            if ($file && $file->isValid() && ! $file->hasMoved()) {
                $nombre = $file->getRandomName();

                //$file->move(WRITEPATH . 'uploads/anexos/formacion', $nombre);
                $file->move('uploads/anexos/formacion', $nombre);

                $idAnexo = $anexoModel->insert([
                    'tipo'             => 'FORMACION',
                    'nombre_original'  => $file->getClientName(),
                    'ruta'             => 'uploads/anexos/formacion/' . $nombre,
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

        $idFormacion = $formacionModel->insert(
            [
                'id_postulante' => $postulanteId,
                'nivel' => $request->getPost('nivel'),
                'carrera' => $request->getPost('carrera'),
                'institucion' => $request->getPost('institucion'),
                'fecha_inicio' => $request->getPost('fecha_inicio'),
                'fecha_fin' => $request->getPost('fecha_fin'),
                'id_anexo' => $idAnexo ?? null,
            ]
        );
        return ['status' => 'success'];
    }


    public function eliminar($userId, $idFormacion)
    {
        $anexoModel = new AnexoModel();
        $postulanteModel = new PostulanteModel();
        $formacionModel = new FormacionModel();


        $postulanteId = $this->getPostulanteId();

        $formacion = $formacionModel
            ->where('id_formacion', $idFormacion)
            ->where('id_postulante', $postulanteId)
            ->first();

        if (!$formacion) {
            return [
                'status' => 'error',
                'message' => 'Registro no encontrado'
            ];
        }

        // eliminar anexos asociados
        $anexos = $anexoModel->where('id_anexo', $formacion['id_anexo'])->findAll();
        foreach ($anexos as $a) {
            if (is_file('' . $a['ruta'])) {
                unlink('' . $a['ruta']);
            }
            /*if (is_file(WRITEPATH . 'uploads/' . $a['ruta'])) {
                unlink(WRITEPATH . 'uploads/' . $a['ruta']);
            }*/
        }

        $anexoModel->where('id_anexo', $formacion['id_anexo'])->delete();
        $formacionModel->delete($idFormacion);

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
