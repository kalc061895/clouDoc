<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $fromEmail  = 'cloudoc@quillasoftware.net.pe';
    public string $fromName   = 'ClouDoc - Tramite Virtual';
    public string $recipients = '';

    // Usar SMTP
    public string $protocol = 'smtp';

    // Configuración Hostinger
    public string $SMTPHost = 'smtp.hostinger.com';
    public string $SMTPUser = 'cloudoc@quillasoftware.net.pe';
    public string $SMTPPass = 'Q8fL^XM$AD/s'; // pon tu clave real
    public int $SMTPPort    = 465; // puerto seguro SSL
    public string $SMTPCrypto = 'ssl'; // obligatorio porque usas 465

    // Timeout
    public int $SMTPTimeout = 10;
    public bool $SMTPKeepAlive = false;

    // Formato del correo
    public string $mailType = 'html';
    public string $charset  = 'UTF-8';
    public bool $wordWrap   = true;
    public int $wrapChars   = 76;

    // Otros
    public bool $validate = true;
    public int $priority  = 3;

    public string $CRLF    = "\r\n";
    public string $newline = "\r\n";

    public bool $BCCBatchMode = false;
    public int $BCCBatchSize  = 200;
    public bool $DSN = false;
}
