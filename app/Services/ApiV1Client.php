<?php

namespace App\Services;

use Config\Services;

class ApiV1Client
{
    private string $baseUrl;
    private int $timeout;

    private function normalizeMessage($message): string
    {
        if (is_array($message)) {
            $parts = [];
            array_walk_recursive($message, static function ($value) use (&$parts): void {
                if (is_scalar($value) || $value === null) {
                    $parts[] = (string) $value;
                }
            });
            return trim(implode(' ', array_filter($parts, static fn(string $v): bool => $v !== '')));
        }

        if (is_scalar($message) || $message === null) {
            return trim((string) $message);
        }

        return '';
    }

    public function __construct()
    {
        $this->baseUrl = rtrim((string) env('API_BASE_URL', ''), '/') . '/';
        $this->timeout = (int) env('API_TIMEOUT', 30);
    }

    public function get(string $path, array $query = []): array
    {
        return $this->request('get', $path, [
            'query' => $query,
        ]);
    }

    public function post(string $path, array $payload = [], array $headers = []): array
    {
        return $this->request('post', $path, [
            'json' => $payload,
            'headers' => $headers,
        ]);
    }

    public function postWithHeaders(string $path, array $payload = [], array $headers = []): array
    {
        return $this->post($path, $payload, $headers);
    }

    public function getWithHeaders(string $path, array $query = [], array $headers = []): array
    {
        return $this->request('get', $path, [
            'query' => $query,
            'headers' => $headers,
        ]);
    }

    public function postForm(string $path, array $form = [], array $headers = []): array
    {
        return $this->request('post', $path, [
            'form_params' => $form,
            'headers' => $headers,
        ]);
    }

    public function postMultipart(string $path, array $multipart = [], array $headers = []): array
    {
        return $this->request('post', $path, [
            'multipart' => $multipart,
            'headers' => $headers,
        ]);
    }

    private function request(string $method, string $path, array $options = []): array
    {
        if ($this->baseUrl === '/') {
            return ['ok' => false, 'status' => 500, 'message' => 'API_BASE_URL is not configured.', 'data' => null, 'raw' => null];
        }

        $url = $this->baseUrl . ltrim($path, '/');
        $client = Services::curlrequest();

        try {
            $response = $client->request(strtoupper($method), $url, array_merge([
                'http_errors' => false,
                'timeout' => $this->timeout,
                'verify' => false,
            ], $options));

            $status = (int) $response->getStatusCode();
            $raw = json_decode((string) $response->getBody(), true);
            $message = is_array($raw) ? $this->normalizeMessage($raw['message'] ?? '') : '';
            $data = is_array($raw) ? ($raw['data'] ?? null) : null;

            return [
                'ok' => $status >= 200 && $status < 300 && is_array($raw) && (($raw['status'] ?? '') === 'success'),
                'status' => $status,
                'message' => $message,
                'data' => $data,
                'raw' => $raw,
            ];
        } catch (\Throwable $e) {
            log_message('error', 'ApiV1Client request failed: ' . strtoupper($method) . ' ' . ltrim($path, '/') . ' - ' . $e->getMessage());
            $message = 'API request failed. Please try again.';
            if ((string) env('CI_ENVIRONMENT', 'production') !== 'production') {
                $message = 'API request failed: ' . $e->getMessage();
            }
            return [
                'ok' => false,
                'status' => 500,
                'message' => $message,
                'data' => null,
                'raw' => null,
            ];
        }
    }
}
