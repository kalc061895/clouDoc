<?php

namespace App\Services\Contratacion;

use App\Models\Contratacion\CapacitacionModel;
use App\Models\Contratacion\AnexoModel;
use App\Models\Contratacion\PostulanteModel;
use App\Models\Contratacion\FormacionModel;

class CapacitacionService
{
    protected $capacitacionModel;
    protected $anexoModel;

    public function __construct()
    {
        $this->capacitacionModel = new CapacitacionModel();
        $this->anexoModel        = new AnexoModel();
    }

    public function listar()
    {
        $postulanteId = $this->getPostulanteId();

        $anexoModel = new AnexoModel();
        $postulanteModel = new PostulanteModel();
        $capacitacionModel = new CapacitacionModel();


        return $capacitacionModel
            ->select('capacitaciones.*, ruta as archivo')
            ->join('anexos', 'anexos.id_anexo = capacitaciones.id_anexo', 'left')
            ->where('id_postulante', $postulanteId)
            ->findAll();
    }

    public function guardar($userId, $request = null)
    {
        $anexoModel = new AnexoModel();
        $postulanteModel = new PostulanteModel();
        $capacitacionModel = new CapacitacionModel();

        $postulanteId = $this->getPostulanteId();

        if ($file = $request->getFile('archivo')) {
            if ($file && $file->isValid() && ! $file->hasMoved()) {
                $nombre = $file->getRandomName();

                //$file->move(WRITEPATH . 'uploads/anexos/capacitacion', $nombre);
                $file->move('uploads/anexos/capacitacion', $nombre);

                $idAnexo = $anexoModel->insert([
                    'tipo'             => 'CAPACITACION',
                    'nombre_original'  => $file->getClientName(),
                    'ruta'     => 'uploads/anexos/capacitacion/' . $nombre,
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

        $idCapacitacion = $this->capacitacionModel->insert([
            'id_postulante' => $postulanteId,
            'nombre'        => $request->getPost('nombre'),
            'institucion'   => $request->getPost('institucion'),
            'horas'         => $request->getPost('horas') ?? 0,
            'fecha_inicio'  => $request->getPost('fecha_inicio'),
            'fecha_fin'     => $request->getPost('fecha_fin'),
            'id_anexo'      => $idAnexo ?? null,
        ]);

        return ['status' => 'success'];
    }

    public function eliminar($idCapacitacion)
    {
        $anexoModel = new AnexoModel();
        $postulanteModel = new PostulanteModel();
        $capacitacionModel = new CapacitacionModel();


        $postulanteId = $this->getPostulanteId();

        $capacitacion = $capacitacionModel
            ->where('id_capacitacion', $idCapacitacion)
            ->where('id_postulante', $postulanteId)
            ->first();
        if (!$capacitacion) {
            return [
                'status' => 'error',
                'message' => 'Registro no encontrasdasddo'
            ];
        }

        // eliminar anexos asociados
        $anexos = $anexoModel->where('id_anexo', $capacitacion['id_anexo'])->findAll();
        foreach ($anexos as $a) {
            if (is_file('' . $a['ruta'])) {
                unlink('' . $a['ruta']);
            }
            /*if (is_file(WRITEPATH . 'uploads/' . $a['ruta'])) {
                unlink(WRITEPATH . 'uploads/' . $a['ruta']);
            }*/
        }

        $anexoModel->where('id_anexo', $capacitacion['id_anexo'])->delete();
        $capacitacionModel->delete($idCapacitacion);

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
