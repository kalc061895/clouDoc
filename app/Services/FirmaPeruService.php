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
            throw new \RuntimeException('Error al obtener token: ' . $resp->getStatusCode());
        }
    
        $body = trim($resp->getBody());
    
        // Si es JSON, decodifica. Si no, es un JWT plano.
        if ($this->isJson($body)) {
            $data = json_decode($body, true);
            $token = $data['token'] ?? $data['access_token'] ?? null;
            if (!$token) {
                throw new \RuntimeException('No se encontró ningún token en la respuesta JSON');
            }
            return $token;
        }
    
        // Respuesta text/plain → devolver directo el JWT
        return $body;
    }
    
    private function isJson(string $string): bool
    {
        json_decode($string);
        return (json_last_error() === JSON_ERROR_NONE);
    }


}
