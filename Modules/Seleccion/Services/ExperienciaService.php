<?php

namespace App\Services\Contratacion;

use App\Models\Contratacion\ExperienciaModel;
use App\Models\Contratacion\AnexoModel;
use App\Models\Contratacion\PostulanteModel;
use CodeIgniter\Shield\Models\UserModel;

class ExperienciaService
{
    protected $experienciaModel;
    protected $anexoModel;

    public function __construct()
    {
        $this->experienciaModel = new ExperienciaModel();
        $this->anexoModel       = new AnexoModel();
    }

    // 📄 Listar experiencias del postulante
    public function listar()
    {
        $anexoModel = new AnexoModel();
        $postulanteModel = new PostulanteModel();
        $experienciaModel = new ExperienciaModel();

        $postulanteId = $this->getPostulanteId();
        return $experienciaModel
            ->select('experiencia_laboral.*, ruta as archivo')
            ->join('anexos', 'anexos.id_anexo = experiencia_laboral.id_anexo', 'left')
            ->where('id_postulante', $postulanteId)
            ->findAll();
    }

    // 💾 Guardar experiencia
    public function guardar($userId, $request = null)
    {
        $postulanteId = $this->getPostulanteId();
        $anexoModel = new AnexoModel();
        $postulanteModel = new PostulanteModel();

        // 2️⃣ Guardar anexo
        if ($file = $request->getFile('archivo')) {
            if ($file && $file->isValid() && ! $file->hasMoved()) {
                $nombre = $file->getRandomName();

                //$file->move(WRITEPATH . 'uploads/anexos/experiencia', $nombre);
                $file->move('uploads/anexos/experiencia', $nombre);

                $idAnexo = $anexoModel->insert([
                    'tipo'             => 'FORMACION',
                    'nombre_original'  => $file->getClientName(),
                    'ruta'     => 'uploads/anexos/experiencia/' . $nombre,
                    'mime'             => $file->getClientMimeType(),
                    'extension'        => $file->getClientExtension(),
                    'size'             => $file->getSize(),
                ]);
            } else {
                return ['status' => 'error', 'message' => 'Error al subir el archivo Max. 10MB'];
            }
        } else {
            return ['status' => 'error', 'message' => 'Error al subir el archivo Max. 10MB'];
        }

        // 1️⃣ Insertar experiencia
        $idExperiencia = $this->experienciaModel->insert([
            'id_postulante'  => $postulanteId,
            'cargo'          => $request->getPost('cargo'),
            'entidad'        => $request->getPost('entidad'),
            'fecha_inicio'   => $request->getPost('fecha_inicio'),
            'fecha_fin'      => $request->getPost('fecha_fin'),
            'id_anexo'       => $idAnexo ?? null
        ]);

        if (!$idExperiencia) {
            throw new \Exception('No se pudo registrar la experiencia');
        } else {
            return ['status' => 'success'];
        }
    }

    // 🗑 Eliminar experiencia (soft delete)
    public function eliminar($idExperiencia)
    {
        $anexoModel = new AnexoModel();
        $postulanteModel = new PostulanteModel();
        $experienciaModel = new ExperienciaModel();


        $postulanteId = $this->getPostulanteId();

        $experiencia = $experienciaModel
            ->where('id_experiencia', $idExperiencia)
            ->where('id_postulante', $postulanteId)
            ->first();
        if (!$experiencia) {
            return [
                'status' => 'error',
                'message' => 'Registro no encontrasdasddo'
            ];
        }

        // eliminar anexos asociados
        $anexos = $anexoModel->where('id_anexo', $experiencia['id_anexo'])->findAll();
        foreach ($anexos as $a) {
            if (is_file('' . $a['ruta'])) {
                unlink('' . $a['ruta']);
            }
            /*if (is_file(WRITEPATH . 'uploads/' . $a['ruta'])) {
                unlink(WRITEPATH . 'uploads/' . $a['ruta']);
            }*/
        }

        $anexoModel->where('id_anexo', $experiencia['id_anexo'])->delete();
        $experienciaModel->delete($idExperiencia);

        return ['status' => 'success'];
    }

    // 🔐 Obtener postulante desde usuario logueado
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
