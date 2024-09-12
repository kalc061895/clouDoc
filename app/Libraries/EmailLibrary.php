<?php namespace App\Libraries;

use CodeIgniter\Config\Services;

class EmailLibrary
{
    protected $email;


    public function __construct()
    {
        $this->email = Services::email();
        $this->email->setFrom('mesadepartes.rssr@gmail.com', 'ClouDoc - Trámite Documentario Virtual');
    }

    public function sendReceiptEmail($expediente, $entidad)
    {
        // Configuración del email
        $this->email->setFrom('mesadepartes.rssr@gmail.com', 'ClouDoc - Trámite Documentario Virtual');
        $this->email->setTo($entidad['correo_electronico']);
        $this->email->setSubject('Cargo de Trámite Virtual - Exp:' . $expediente['numero_expediente']);
        $this->email->setCC('mesadepartes.rssr@gmail.com');
        // Cargar la vista para el cuerpo del mensaje
        $message = view('email/receipt_email', [
            'entidad' => $entidad,
            'expediente' => $expediente
        ]);

        $this->email->setMessage($message);
        $this->email->setMailType('html');

        // Adjuntar el PDF (si es necesario)
        // $this->email->attach('receipts/' . $expediente['numero_expediente'] . '.pdf');

        // Enviar el email
        if ($this->email->send()) {
            return true;
        } else {
            return false;
        }
    }
    public function sendNotificationEmail($expediente, $entidad, $body)
    {
        // Configuración del email
        $this->email->setFrom('mesadepartes.rssr@gmail.com', 'ClouDoc - Trámite Documentario Virtual');
        $this->email->setTo($entidad['correo_electronico']);
        $this->email->setSubject('Expediente '. $body['estado'].' - Trámite Virtual - Exp:' . $expediente['numero_expediente']);

        // Cargar la vista para el cuerpo del mensaje
        $message = view('email/notification_email', [
            'entidad' => $entidad,
            'expediente' => $expediente,
            'body' => $body,
        ]);

        $this->email->setMessage($message);
        $this->email->setMailType('html');

        // Adjuntar el PDF (si es necesario)
        // $this->email->attach('receipts/' . $expediente['numero_expediente'] . '.pdf');

        // Enviar el email
        if ($this->email->send()) {
            return true;
        } else {
            return false;
        }
    }
}
