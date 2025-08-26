<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Config\Services;

class MailTestController extends BaseController
{
    public function index()
    {
        $email = Services::email();

        $email->setFrom('cloudoc@quillasoftware.net.pe', 'ClouDoc - Tramite Virtual');
        $email->setTo('mlgmarveled@gmail.com');
        $email->setSubject('📧 Prueba de correo desde ClouDoc');
        $email->setMessage('<h3>¡Hola!</h3><p>Este es un correo de prueba enviado desde <b>CodeIgniter 4</b> usando Hostinger SMTP.</p>');

        if ($email->send()) {
            echo "✅ Correo enviado correctamente.";
        } else {
            echo "❌ Error al enviar correo:<br><br>";
            print_r($email->printDebugger(['headers']));
        }
    }
}
