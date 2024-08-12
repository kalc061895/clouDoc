<?php

namespace App\Libraries;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use PhpParser\Node\Stmt\TryCatch;

class GoogleDrive
{
    private $client;
    private $service;

    public function __construct()
    {
        $this->client = $this->getClient();
        $this->service = new Drive($this->client);
    }

    private function getClient()
    {
        $client = new Client();
        $client->setApplicationName('Google Drive API PHP Quickstart');
        $client->setScopes(Drive::DRIVE);
        try {
            $client->setAuthConfig(APPPATH . 'Credentials/credentials.json'); // Ruta a tu archivo credentials
            $client->setAccessType('offline');
            $client->setPrompt('select_account consent');

            $tokenPath = APPPATH . 'Credentials/token.json';
        } catch (\Throwable $th) {
            //throw $th;
        }
        $client->setAuthConfig(APPPATH . 'Credentials/credentials.json'); // Ruta a tu archivo credentials
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        $tokenPath = APPPATH . 'Credentials/token.json';
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }

        if ($client->isAccessTokenExpired()) {
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                $authUrl = $client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                print 'Enter verification code: ';
                $authCode = trim(fgets(STDIN));

                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

                if (array_key_exists('error', $accessToken)) {
                    throw new \Exception(join(', ', $accessToken));
                }
            }

            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }

        return $client;
    }

    public function uploadFile($fileTmpPath, $fileName, $folderId)
    {
        $fileMetadata = new DriveFile(array(
            'name' => $fileName,
            'parents' => array($folderId)
        ));

        $content = file_get_contents($fileTmpPath);
        $file = $this->service->files->create($fileMetadata, array(
            'data' => $content,
            'mimeType' => mime_content_type($fileTmpPath),
            'uploadType' => 'multipart',
            'fields' => 'id'
        ));

        return $file->id;
    }

    public function createFolder($folderName, $parentFolderId = null)
    {
        $fileMetadata = new DriveFile(array(
            'name' => $folderName,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents' => $parentFolderId ? array($parentFolderId) : null
        ));

        $folder = $this->service->files->create($fileMetadata, array(
            'fields' => 'id'
        ));

        return $folder->id;
    }
}
