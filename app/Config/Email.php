<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $fromEmail = 'kalaruta@rissanroman.gob.pe';
    public string $fromName  = 'ClouDoc - Trámite Virtual';

    public string $protocol = 'smtp';

    // Servidor SMTP
    public string $SMTPHost = 'mail.rissanroman.gob.pe';
    public string $SMTPUser = 'kalaruta@rissanroman.gob.pe';
    public string $SMTPPass = 'lVwIq6za~k1@[LuL'; // ⚠️ NO subir a git
    public int    $SMTPPort = 465;
    public string $SMTPCrypto = 'ssl';

    // Opciones SMTP
    public int  $SMTPTimeout = 10;
    public bool $SMTPKeepAlive = false;

    // Formato del correo
    public string $mailType = 'html';
    public string $charset  = 'UTF-8';
    public bool   $wordWrap = true;
    public int    $wrapChars = 76;

    // Validaciones
    public bool $validate = true;
    public int  $priority = 3;

    public string $CRLF    = "\r\n";
    public string $newline = "\r\n";
}
