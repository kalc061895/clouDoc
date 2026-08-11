<?php

namespace Modules\Seleccion\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Seleccion\Services\ConvocatoriaService;
use Modules\Seleccion\Models\PostulacionModel;
use Modules\Seleccion\Models\FormacionModel;
use Modules\Seleccion\Models\ExperienciaModel;
use Modules\Seleccion\Models\ExtraModel;
use Modules\Seleccion\Models\PlazaModel;
use Modules\Seleccion\Models\PostulanteModel;
use Modules\Seleccion\Models\ConvocatoriaModel;


use Dompdf\Dompdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

class PostulacionController extends BaseController
{
    protected $service;
    public function __construct()
    {
        $this->service = new ConvocatoriaService();
    }
    public function iniciar()
    {
        try {
            $service = new ConvocatoriaService();

            $idPostulacion = $service->iniciarPostulacion(
                auth()->id(),
                (int) $this->request->getPost('id_convocatoria')
            );

            return $this->response->setJSON([
                'status' => 'ok',
                'id_postulacion' => $idPostulacion
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function confirmarPostulacion()
    {
        try {
            $service = new ConvocatoriaService();

            $idPostulacion = $service->confirmarPostulacion(
                auth()->id(),
                (int) $this->request->getPost('id_plaza')
            );
            $email = $this->enviarCorreoConfirmacion($idPostulacion);
            return $this->response->setJSON([
                'email' => $email,
                'status' => 'success',
                'message' => 'Se ha registrado Correctamente tu postulación, en breve recibirás un correo para tu confirmacion',
                'id_postulacion' => $idPostulacion
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function constancia($idPostulacion)
    {
        $postulacionModel = new PostulacionModel();
        $formacionModel   = new FormacionModel();
        $experienciaModel = new ExperienciaModel();
        $extraModel       = new ExtraModel();
        $plazaModel       = new PlazaModel();
        $postulanteModel  = new PostulanteModel();
        $convocatoriaModel = new ConvocatoriaModel();
    
    
        // 👤 Postulante
        
        $postulanteId = $postulanteModel
            ->where('user_id', auth()->id())
            ->first()['id_postulante'];
        

        // 🔐 Postulante Datos Personales
        
        $postulante = $postulanteModel
            ->join('auth_identities u', 'u.user_id = postulantes.user_id')
            ->where('id_postulante', $postulanteId)
            ->first();

        // 🔐 Postulación (seguridad incluida)
        $postulacion = $postulacionModel
            ->where('id_postulacion', $idPostulacion)
            ->where('id_postulante', $postulanteId)
            ->first();
        // 🔐 convocatoria (seguridad incluida)
        $convocatoria = $convocatoriaModel
            ->where('id_convocatoria', $postulacion['id_convocatoria'])
            ->first();

        if (!$postulacion) {
            return redirect()->back()->with('error', 'Postulación no encontrada');
        }


        // 🏢 Plaza
        $plaza = $plazaModel->find($postulacion['id_plaza']);

        // 🎓 Formación
        $formaciones = $formacionModel
            ->where('id_postulante', $postulanteId)
            ->findAll();

        // 💼 Experiencia
        $experiencias = $experienciaModel
            ->where('id_postulante', $postulanteId)
            ->findAll();

        // 📎 Información extra
        $extras = $extraModel
            ->where('id_postulante', $postulanteId)
            ->findAll();

        // 🔳 QR
        $urlVerificacion = base_url('verificar/postulacion/' . $postulacion['id_postulacion']);



        // 📄 Vista PDF
        $html = view('contratacion/pdf/constancia_postulacion', [
            'postulacion'  => $postulacion,
            'usuario'      => $postulante,
            'convocatoria' => $convocatoria,
            'plaza'        => $plaza,
            'formaciones'  => $formaciones,
            'experiencias' => $experiencias,
            'extras'       => $extras,
        ]);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setBody($dompdf->output());
    }
    
    public function constanciaAdmin($idPostulacion)
    {
        $postulacionModel = new PostulacionModel();
        $formacionModel   = new FormacionModel();
        $experienciaModel = new ExperienciaModel();
        $extraModel       = new ExtraModel();
        $plazaModel       = new PlazaModel();
        $postulanteModel  = new PostulanteModel();
        $convocatoriaModel = new ConvocatoriaModel();
    
    
        // 👤 Postulante
        
        
        $postulanteId = $postulacionModel
            ->where('id_postulacion', $idPostulacion)
            ->first()['id_postulante'];
        
    
        // 🔐 Postulante Datos Personales
        
        $postulante = $postulanteModel
            ->join('auth_identities u', 'u.user_id = postulantes.user_id')
            ->where('id_postulante', $postulanteId)
            ->first();

        // 🔐 Postulación (seguridad incluida)
        $postulacion = $postulacionModel
            ->where('id_postulacion', $idPostulacion)
            ->where('id_postulante', $postulanteId)
            ->first();
        // 🔐 convocatoria (seguridad incluida)
        $convocatoria = $convocatoriaModel
            ->where('id_convocatoria', $postulacion['id_convocatoria'])
            ->first();

        if (!$postulacion) {
            return redirect()->back()->with('error', 'Postulación no encontrada');
        }


        // 🏢 Plaza
        $plaza = $plazaModel->find($postulacion['id_plaza']);

        // 🎓 Formación
        $formaciones = $formacionModel
            ->where('id_postulante', $postulanteId)
            ->findAll();

        // 💼 Experiencia
        $experiencias = $experienciaModel
            ->where('id_postulante', $postulanteId)
            ->findAll();

        // 📎 Información extra
        $extras = $extraModel
            ->where('id_postulante', $postulanteId)
            ->findAll();

        // 🔳 QR
        $urlVerificacion = base_url('verificar/postulacion/' . $postulacion['id_postulacion']);



        // 📄 Vista PDF
        $html = view('contratacion/pdf/constancia_postulacion', [
            'postulacion'  => $postulacion,
            'usuario'      => $postulante,
            'convocatoria' => $convocatoria,
            'plaza'        => $plaza,
            'formaciones'  => $formaciones,
            'experiencias' => $experiencias,
            'extras'       => $extras,
        ]);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setBody($dompdf->output());
    }

    private function enviarCorreoConfirmacion(int $idPostulacion)
    {
        
        $postulacionModel  = new PostulacionModel();
        $convocatoriaModel = new ConvocatoriaModel();
        $postulanteModel   = new PostulanteModel();
        $plazaModel       = new PlazaModel();

         $usuario = $postulanteModel
        ->select('postulantes.nombres, postulantes.paterno, postulantes.materno, ai.secret AS email')
        ->join('auth_identities ai', 'ai.user_id = postulantes.user_id')
        ->where('postulantes.user_id', auth()->id())
        ->first();
    
        if (!$usuario) {
            return false;
        }
    
        $email = \Config\Services::email();
    
        $email->setTo($usuario['email']);
        $email->setSubject('Postulación registrada');
    
        // MENSAJE SIMPLE
        $mensaje = "
            <p>Hola <strong>{$usuario['nombres']} {$usuario['paterno']}</strong>,</p>
            <p>Tu postulación fue registrada correctamente en el sistema.</p>
            <p>Te notificaremos cualquier actualización por este medio.</p>
            <br>
            <p><small>Este es un mensaje automático, por favor no responder.</small></p>
        ";
    
        $email->setMessage($mensaje);
    
        return $email->send();
    }
}
