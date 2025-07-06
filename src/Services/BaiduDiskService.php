<?php

namespace DagaSmart\CloudStorage\Services;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class BaiduDiskService
{
    private Client $client;
    private string $accessToken;
    protected string $refreshToken;
    protected int $expiresIn;

    public function __construct()
    {
        $this->client = new Client();
        // 获取 access token
        $this->getAccessToken();
    }

    private function getAccessToken()
    {
        try {
            $uri = 'https://oauth.api.map.baidu.com/oauth/2.0/token';
            $response = $this->client->request('POST', $uri, [
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => env('BAIDU_APP_KEY'),
                    'client_secret' => env('BAIDU_SECRET_KEY'),
                ],
            ]);

            $body = json_decode( $response->getBody(), true);
            if (isset( $body['access_token'])) {
                $this->accessToken = $body['access_token'];
                $this->refreshToken = $body['refresh_token'];
                $this->expiresIn = $body['expires_in'];
            } else {
                Log::error('Failed to get access token from Baidu Disk:', ['response' => $body]);
            }
        } catch (Exception $e) {
            Log::error('Error occurred while getting access token from Baidu Disk:', ['exception' => $e->getMessage()]);
        }
    }

    public function uploadFile( $filePath, $fileName)
    {
        try {
            $multipartData = [
                [
                    'name' => 'file',
                    'contents' => fopen( $filePath, 'r'),
                    'filename' => $fileName,
                ],
                [
                    'name' => 'path',
                    'contents' => '/' . $fileName,
                ],
                [
                    'name' => 'ondup',
                    'contents' => 'newcopy',
                ],
            ];
            $uri = 'https://pan.baidu.com/rest/2.0/xpan/file?method=upload&access_token=' . $this->accessToken;
            $response = $this->client->request('POST', $uri, [
                'multipart' => $multipartData,
            ]);

            return json_decode( $response->getBody(), true);
        } catch (Exception $e) {
            Log::error('Error occurred while uploading file to Baidu Disk:', ['exception' => $e->getMessage()]);
            return null;
        }
    }
}
