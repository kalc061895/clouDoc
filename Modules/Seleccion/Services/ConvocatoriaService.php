<?php

namespace App\Services\Contratacion;

use App\Models\Contratacion\ConvocatoriaModel;
use App\Models\Contratacion\PostulacionModel;
use App\Models\Contratacion\PostulanteModel;
use App\Models\Contratacion\PlazaModel;



class ConvocatoriaService
{
    protected $convocatoriaModel;
    protected $postulanteModel;
    protected $postulacionModel;

    protected $plazaModel;

    public function __construct()
    {
        $this->convocatoriaModel = new ConvocatoriaModel();
        $this->postulanteModel = new PostulanteModel();
        $this->postulacionModel = new PostulacionModel();
    }

    public function listar()
    {
        return $this->convocatoriaModel->orderBy('id_convocatoria', 'DESC')->findAll();
    }

    public function obtener($id)
    {
        return $this->convocatoriaModel->find($id);
    }

    public function guardar(array $data): array
    {
        if ($data['fecha_inicio'] > $data['fecha_fin']) {
            return [
                'status' => 'error',
                'message' => 'La fecha inicio no puede ser mayor a la fecha fin'
            ];
        }

        if (!empty($data['id_convocatoria'])) {
            $this->convocatoriaModel->update($data['id_convocatoria'], $data);
            $msg = 'Convocatoria actualizada';
        } else {
            $this->convocatoriaModel->insert($data);
            $msg = 'Convocatoria registrada';
        }

        return [
            'status' => 'success',
            'message' => $msg
        ];
    }

    public function eliminar($id): array
    {
        if (!$this->convocatoriaModel->find($id)) {
            return [
                'status' => 'error',
                'message' => 'Convocatoria no encontrada'
            ];
        }

        $this->convocatoriaModel->delete($id);

        return [
            'status' => 'success',
            'message' => 'Convocatoria eliminada'
        ];
    }

    public function listarVigentes()
    {
        $hoy = date('Y-m-d');

        $postulanteModel= new PostulanteModel();
        $existePostulante = $postulanteModel
            ->where('user_id', auth()->id())
            ->first();
        if (!$existePostulante) {
            $postulanteModel->insert([
                'user_id' => auth()->id()
            ]);
        }

        $postulanteId = $this->getPostulanteId();

        return $this->convocatoriaModel
            ->select('
            convocatorias.*,
            p.id_postulacion,
            p.estado AS estado_postulacion
        ')
            ->join(
                'postulaciones p',
                'p.id_convocatoria = convocatorias.id_convocatoria
             AND p.id_postulante = ' . $postulanteId,
                'left'
            )
            ->where('convocatorias.estado', 'PUBLICADO')
            ->where('convocatorias.fecha_inicio <=', $hoy)
            ->where('convocatorias.fecha_fin >=', $hoy)
            ->orderBy('convocatorias.fecha_fin', 'ASC')
            ->findAll();
    }

    public function iniciarPostulacion(int $userId, int $idConvocatoria): int
    {
        // 1️⃣ Obtener postulante desde user
        $postulanteId = $this->getPostulanteId();


        // 2️⃣ Verificar si ya existe postulación
        $post = $this->postulacionModel
            ->where('id_postulante', $postulanteId)
            ->where('id_convocatoria', $idConvocatoria)
            ->first();

        // 3️⃣ Crear si no existe
        if (!$post) {
            return $this->postulacionModel->insert([
                'id_postulante'   => $postulanteId,
                'id_convocatoria' => $idConvocatoria,
                'estado'          => 'REGISTRANDO',
            ]);
        }

        return $post['id_postulacion'];
    }

    public function listarPlazas()
    {
        $hoy = date('Y-m-d');

        return $this->plazaModel
            ->where('estado', 'PUBLICADO')
            ->where('fecha_inicio <=', $hoy)
            ->where('fecha_fin >=', $hoy)
            ->orderBy('fecha_fin', 'ASC')
            ->findAll();
    }

    public function confirmarPostulacion(int $userId, int $idPlaza): int
    {
        // 1️⃣ Obtener postulante desde user
        $postulanteId = $this->getPostulanteId();

        // 2️⃣ Verificar si ya existe postulación
        $post = $this->postulacionModel
            ->where('id_postulante', $postulanteId)
            ->where('estado', 'REGISTRANDO')
            ->first();

        // 3️⃣ Crear si no existe
        if ($post) {
            return $this->postulacionModel->update(
                $post['id_postulacion'],
                [
                    'id_plaza'   => $idPlaza,
                    'estado'     => 'POSTULADO',
                    'fecha_postulacion' => date('Y-m-d H:i:s')
                ]
            );
        }


        return $post['id_postulacion'];
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
            return false;
        }

        return $postulante['id_postulante'];
    }
    public function convocatoriasPublicadas()
    {
        $convocatoriaModel = new ConvocatoriaModel();
        return $convocatoriaModel
            ->select('id_convocatoria, codigo, titulo')
            ->where('estado', 'PUBLICADO')
            ->orderBy('fecha_fin', 'DESC')
            ->get()
            ->getResultArray();
    }

    // 🔹 Plazas + cantidad de postulantes por convocatoria
    public function plazasPorConvocatoria(int $idConvocatoria)
    {
        $plazaModel = new PlazaModel();
        return $plazaModel
            ->select("
                plazas.id_plaza,
                plazas.codigo_plaza,
                plazas.cargo,
                COUNT(p.id_postulacion) AS total_postulantes
            ")
            ->join(
                'postulaciones p',
                'p.id_plaza = plazas.id_plaza AND p.estado = "POSTULADO"',
                'left'
            )
            ->join(
                'postulantes pt',
                'pt.id_postulante = p.id_postulante',
                'left'
            )
            ->where('plazas.id_convocatoria', $idConvocatoria)
            ->where('pt.dni !=','')
            ->groupBy('plazas.id_plaza')
            ->orderBy('plazas.codigo_plaza', 'ASC')
            ->get()
            ->getResultArray();
    }
    
    
}
