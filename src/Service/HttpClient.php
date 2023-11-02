<?php

namespace App\Service;

class HttpClient
{
    public function getUrl($url, $headers): array
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPGET => true,
            CURLOPT_HTTPHEADER => $headers
        ]);
        $response = curl_exec($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return [
            'statusCode' => $statusCode,
            'message' => $response
        ];
    }

}