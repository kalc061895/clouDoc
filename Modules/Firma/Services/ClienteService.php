<?php

namespace Modules\Firma\Services;

use Modules\Firma\Config\Firma;

class ClienteService
{
    public function parametros(string $token): string
    {
        $op = (new FirmaService())->porToken($token);
        $cfg = config(Firma::class);
        $id = $cfg->clientId ?: (string) env('FIRMAPERU_CLIENT_ID', '');
        $secret = $cfg->clientSecret ?: (string) env('FIRMAPERU_CLIENT_SECRET', '');
        if ($id === '' || $secret === '') throw new \RuntimeException('Configure las credenciales institucionales de Firma Perú.');
        $response = \Config\Services::curlrequest()->post($cfg->tokenUrl, [
            'form_params' => ['client_id' => $id, 'client_secret' => $secret],
            'http_errors' => false, 'timeout' => 20, 'connect_timeout' => 10,
        ]);
        if ($response->getStatusCode() !== 200) throw new \RuntimeException('Firma Perú no pudo autorizar la operación.');
        $body = trim($response->getBody());
        $json = json_decode($body, true);
        $accessToken = is_array($json) ? ($json['token'] ?? $json['access_token'] ?? '') : $body;
        if (! is_string($accessToken) || $accessToken === '' || str_contains($accessToken, '<')) throw new \RuntimeException('Respuesta de autorización inválida.');
        return base64_encode(json_encode([
            'signatureFormat' => 'PAdES', 'signatureLevel' => 'B', 'signaturePackaging' => 'enveloped',
            'documentToSign' => $cfg->url('firma/cliente/documento/' . $token),
            'certificateFilter' => '.*', 'webTsa' => '', 'userTsa' => '', 'passwordTsa' => '',
            'theme' => 'claro', 'visiblePosition' => true, 'contactInfo' => '',
            'signatureReason' => $op['motivo'], 'bachtOperation' => false, 'oneByOne' => false,
            'signatureStyle' => (int) $op['estilo'], 'imageToStamp' => $cfg->stampUrl,
            'stampTextSize' => 14, 'stampWordWrap' => 37, 'role' => $op['cargo'],
            'stampPage' => 1, 'positionx' => 20, 'positiony' => 20,
            'uploadDocumentSigned' => $cfg->url('firma/cliente/recibir/' . $token),
            'certificationSignature' => false, 'token' => $accessToken,
        ], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));
    }
}
