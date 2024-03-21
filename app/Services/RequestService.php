<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RequestService
{
    /**
     * @param string $url
     * @return array
     */
    public function sendRequest(string $url): array
    {
        try {
            $response = Http::get($url);

            if ($response->successful()) {
                return [
                    'status' => 'success',
                    'data' => $response->json()
                ];
            } else {
                $errMsg = 'HTTP request was not successful. Status code: ' . $response->status();
                Log::error($errMsg);
                return [
                    'status' => 'error',
                    'message' => $errMsg,
                    'code' => $response->status()
                ];
            }

        } catch (\Exception $exception) {
            Log::error('Error in request: ' . $exception->getMessage());
            return [
                'status' => 'error',
                'message' => $exception->getMessage(),
                'code' => $exception->getCode()
            ];
        }
    }
}
