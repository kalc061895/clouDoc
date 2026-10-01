<?php

namespace Modules\Firma\Config;

use CodeIgniter\Config\BaseConfig;

class Firma extends BaseConfig
{
    public string $publicBase = '';
    public string $clientId = '';
    public string $clientSecret = '';
    public string $tokenUrl = 'https://apps.firmaperu.gob.pe/admin/api/security/generate-token';
    public string $scriptUrl = 'https://apps.firmaperu.gob.pe/web/clienteweb/firmaperu.min.js';
    public string $stampUrl = 'https://rissanroman.gob.pe/firma/img/rssr-logo.png';
    public int $port = 48596;
    public int $ttl = 1800;
    public int $maxBytes = 20971520;

    public function url(string $path): string
    {
        return $this->publicBase !== '' ? rtrim($this->publicBase, '/') . '/' . ltrim($path, '/') : base_url($path);
    }
}
