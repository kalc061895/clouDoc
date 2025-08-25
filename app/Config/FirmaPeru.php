<?php

// app/Config/FirmaPeru.php
namespace App\Config;

use CodeIgniter\Config\BaseConfig;

class FirmaPeru extends BaseConfig
{
    public string $fwAuthPath = WRITEPATH . 'firmaperu/fwAuthorization.json';
    public string $publicBase = "http://localhost/cloudoc/public"; // ajusta a tu dominio
}
