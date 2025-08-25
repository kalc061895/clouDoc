<?php 

// app/Services/FirmaPeruService.php
namespace App\Services;

use App\Config\FirmaPeru;

class FirmaPeruService
{
    protected array $auth;

    public function __construct(protected FirmaPeru $cfg)
    {
        $json = json_decode(file_get_contents($cfg->fwAuthPath), true);
        if (!is_array($json) || empty($json['client_id']) || empty($json['client_secret']) || empty($json['token_url'])) {
            throw new \RuntimeException('fwAuthorization.json inválido');
        }
        $this->auth = $json;
    }

    public function getAccessToken(): string
    {
        $client = \Config\Services::curlrequest();
        $resp = $client->post($this->auth['token_url'], [
            'form_params' => [
                'client_id'     => $this->auth['client_id'],
                'client_secret' => $this->auth['client_secret'],
            ],
            'http_errors' => false,
            'timeout'     => 10,
        ]);

        if ($resp->getStatusCode() !== 200) {
            throw new \RuntimeException('Error al obtener token: '.$resp->getStatusCode());
        }
        $data = json_decode($resp->getBody(), true);
        if (!$data || empty($data['token'])) { // el campo se entrega como token JWT
            // Algunas instalaciones devuelven el JWT como 'access_token'. Ajusta si aplica.
            $token = $data['token'] ?? $data['access_token'] ?? null;
            if (!$token) throw new \RuntimeException('Respuesta de token inválida');
            return $token;
        }
        return $data['token'];
    }
}
