<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\FirmaPeruService;
use CodeIgniter\HTTP\ResponseInterface;
use Ramsey\Uuid\Uuid;

class SignatureController extends BaseController
{
    public function index()

    {
        return view('test/firma');
    }

    // POST /api/signature/job
    public function createJob(): ResponseInterface
    {
        // Autentica a tu usuario como corresponda (omito por brevedad)
        $userId    = 1; // <- ejemplo
        // usuario para firmar

        $profileId = (int)($this->request->getPost('profile_id') ?? 1);

        $file = $this->request->getFile('document');
        if (!$file || !$file->isValid()) {
            
            //return $this->fail('Archivo inválido', 400);
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Archivo inválido']);
        }

        $ext = strtolower($file->getClientExtension() ?: 'pdf');

        $uuid = Uuid::uuid4()->toString();
        $stored = $file->store("signature/original/{$userId}", "{$uuid}.{$ext}");
        
        //if (!$stored) return $this->fail('No se pudo guardar el archivo', 500);
        if (!$stored){
            //return $this->fail('No se pudo guardar el archivo', 500);
            return $this->response->setStatusCode(500)->setJSON(['error' => 'No se pudo guardar el archivo']);
        } 

        // Crea job
        $paramToken = bin2hex(random_bytes(16));
        $expiresAt  = (new \DateTime('+15 minutes'))->format('Y-m-d H:i:s');

        $db = db_connect();
        $db->table('signature_jobs')->insert([
            'id'            => $uuid,
            'user_id'       => $userId,
            'profile_id'    => $profileId,
            'original_name' => $file->getClientName(),
            'mime'          => $file->getClientMimeType(),
            'size'          => $file->getSize(),
            'document_path' => WRITEPATH.'uploads/'.$stored,
            'param_token'   => $paramToken,
            'expires_at'    => $expiresAt,
            'status'        => 'pending',
            'created_at'    => date('Y-m-d H:i:s'),
        ]);

        // Construye el "param" que consumirá startSignature()
        $param = [
            'param_url'          => base_url('api/signature/param'),
            'param_token'        => $paramToken,
            'document_extension' => $ext,
        ];
        
        return $this->response->setJSON([
            'job_id'      => $uuid,
            'param_base64'=> base64_encode(json_encode($param)),
            'param'=> json_encode($param),
            'port'        => 48596, // usa éste por defecto
        ]);
    }

    // POST /api/signature/param   (x-www-form-urlencoded: param_token=...)
    public function param()
    {
        $paramToken = $this->request->getPost('param_token');
        //if (!$paramToken) return $this->fail('Falta param_token', 400);
        
        if (!$paramToken) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Falta param_token']);
            //return $this->fail('Falta param_token', 400);
        }

        $db   = db_connect();
        $job  = $db->table('signature_jobs')->where('param_token',$paramToken)->get()->getRow();
        if (!$job) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Token inválido']);
            //return $this->fail('Token inválido', 404);
        }
        //if (!$job) return $this->fail('Token inválido', 404);
        if ($job->status !== 'pending') {
            return $this->response->setStatusCode(409)->setJSON(['error' => 'Job no está pendiente']);
            //return $this->fail('Job no está pendiente', 409);
        }
        //if ($job->status !== 'pending') return $this->fail('Job no está pendiente', 409);
        if ($job->expires_at && strtotime($job->expires_at) < time()) 
        {

            //return $this->response->setStatusCode(410)->setJSON(['error' => 'Token expirado']);
            //return $this->fail('Token expirado', 410);
        }

        // Lee el perfil
        
        $profile = $db->table('signature_profiles')->where('id',$job->profile_id)->get()->getRow();
        
        if (!$profile) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Perfil no encontrado']);
            //return $this->fail('Perfil no encontrado', 404);
        }
        //if (!$profile) return $this->fail('Perfil no encontrado', 404);

        // Arma URLs públicas
        $docUrl   = site_url('api/signature/doc/'.$job->id);
        $uploadUrl= site_url('api/signature/upload/'.$job->id);

        // Pide el JWT del servicio
        $token = service('FirmaPeruService') instanceof FirmaPeruService
               ? service('FirmaPeruService')->getAccessToken()
               : (new FirmaPeruService(new \App\Config\FirmaPeru()))->getAccessToken();

        // Construye el JSON de parámetros según guía oficial
        $payload = [
            'signatureFormat'       => $profile->signatureFormat,
            'signatureLevel'        => $profile->signatureLevel,
            'signaturePackaging'    => $profile->signaturePackaging,
            'documentToSign'        => $docUrl,
            'certificateFilter'     => $profile->certificateFilter,
            'webTsa'                => (string)$profile->webTsa,
            'userTsa'               => (string)$profile->userTsa,
            'passwordTsa'           => (string)$profile->passwordTsa,
            'theme'                 => $profile->theme,
            'visiblePosition'       => (bool)$profile->visiblePosition,
            'contactInfo'           => (string)($profile->contactInfo ?? ''),
            'signatureReason'       => (string)$profile->signatureReason,
            // ¡Mantén el nombre tal cual está en la guía!
            'bachtOperation'        => (bool)$profile->bachtOperation,
            'oneByOne'              => (bool)$profile->oneByOne,
            'signatureStyle'        => (int)$profile->signatureStyle,
            'imageToStamp'          => (string)($profile->imageToStamp ?? ''),
            'stampTextSize'         => (int)$profile->stampTextSize,
            'stampWordWrap'         => (int)$profile->stampWordWrap,
            'role'                  => (string)$profile->role,
            'stampPage'             => (int)$profile->stampPage,
            'positionx'             => (int)$profile->positionx,
            'positiony'             => (int)$profile->positiony,
            'uploadDocumentSigned'  => $uploadUrl,
            'certificationSignature'=> (bool)$profile->certificationSignature,
            'token'                 => $token,
        ];

        // Debes responder **texto plano** con el Base64 del JSON
        return $this->response
            ->setContentType('text/plain')
            ->setBody(base64_encode(json_encode($payload, JSON_UNESCAPED_SLASHES)));
    }

    // GET /api/signature/doc/{id}  -> devuelve el PDF a firmar
    public function doc(string $id): ResponseInterface
    {
        $job = db_connect()->table('signature_jobs')->where('id',$id)->get()->getRow();
        if (!$job) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'No encontrado']);
            //return $this->fail('No encontrado', 404);
        }
        //if (!$job) return $this->fail('No encontrado', 404);
        return $this->response->download($job->document_path, null)->setFileName($job->original_name);
    }

    // POST /api/signature/upload/{id}  (signed_file)
    public function upload(string $id): ResponseInterface
    {
        $file = $this->request->getFile('signed_file'); // según guía oficial
        if (!$file || !$file->isValid()){
            return $this->response->setStatusCode(400)->setJSON(['error' => 'signed_file faltante']);
            //return $this->fail('signed_file faltante', 400);
        } 
        //if (!$file || !$file->isValid()) return $this->fail('signed_file faltante', 400);

        $dest = $file->store("signature/signed", "{$id}.pdf");

        $db = db_connect();
        $db->table('signature_jobs')->where('id',$id)->update([
            'status'      => 'signed',
            'signed_path' => WRITEPATH.'uploads/'.$dest,
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON(['ok'=>true]);
    }
}