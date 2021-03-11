<?php

use Illuminate\Support\Facades\Http;

function getUser($user_id)
{
    $url = env('URL_SERVICE_USER') . "users/{$user_id}";
    try {
        $res = Http::timeout(10)->get($url);
        $data = $res->json();
        $data['http_code'] = $res->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'service user unavailable'
        ];
    }
}

function getUserById($user_id = [])
{
    $url = env('URL_SERVICE_USER') . 'users/';
    try {
        if (count($user_id) === 0) {
            return response()->json([
                'status' => 'success',
                'http_code' => 200,
                'data' => []
            ]);
        }
        $res = Http::timeout(10)->get($url, ['user_ids[]' => $user_id]);
        $data = $res->json();
        $data['status_code'] = $res->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'service user unavailable'
        ];
    }
}
