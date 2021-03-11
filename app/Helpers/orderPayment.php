<?php

use Illuminate\Support\Facades\Http;

function postOrder($data)
{
    try {
        $url = env('URL_SERVICE_PAYMENT') . 'api/orders';
        $http = Http::post($url, $data);
        $data = $http->json();
        $data['http_code'] = $http->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'service order payment unavailable'
        ];
    }
}
