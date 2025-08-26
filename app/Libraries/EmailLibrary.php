<?php namespace App\Libraries;

use CodeIgniter\Config\Services;

class EmailLibrary
{
    protected $email;

    public function __construct()
    {
        // Solo crea la instancia, NO seteamos nada aquí todavía
        $this->email = Services::email();
    }

    public function sendReceiptEmail($expediente, $entidad)
    {
        // Reinicia el email para evitar que arrastre headers previos
        $this->email->clear(true);

        $this->email->setFrom('cloudoc@quillasoftware.net.pe', 'ClouDoc - Trámite Documentario Virtual');
        $this->email->setTo($entidad['correo_electronico']);
        $this->email->setCC('mesadepartes.rssr@gmail.com');
        $this->email->setSubject('Cargo de Trámite Virtual - Exp:' . $expediente['numero_expediente']);

        $message = view('email/receipt_email', [
            'entidad'   => $entidad,
            'expediente'=> $expediente
        ]);
        $this->email->setMessage($message);
        $this->email->setMailType('html');

        if ($this->email->send()) {
            return true;
        } else {
            log_message('error', 'Email error: ' . print_r($this->email->printDebugger(['headers', 'subject', 'body']), true));
            return false;
        }
    }

    public function sendNotificationEmail($expediente, $entidad, $body)
    {
        // Reinicia la instancia para este envío
        $this->email->clear(true);

        $this->email->setFrom('cloudoc@quillasoftware.net.pe', 'ClouDoc - Trámite Documentario Virtual');
        $this->email->setTo($entidad['correo_electronico']);
        $this->email->setSubject('Expediente ' . $body['estado'] . ' - Trámite Virtual - Exp:' . $expediente['numero_expediente']);

        $message = view('email/notification_email', [
            'entidad'   => $entidad,
            'expediente'=> $expediente,
            'body'      => $body,
        ]);
        $this->email->setMessage($message);
        $this->email->setMailType('html');

        if ($this->email->send()) {
            return true;
        } else {
            log_message('error', 'Email error: ' . print_r($this->email->printDebugger(['headers', 'subject', 'body']), true));
            return false;
        }
    }
}
