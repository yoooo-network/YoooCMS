<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class ApiAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Skip for OPTIONS requests (CORS preflight)
        if (strtoupper($request->getMethod()) === 'OPTIONS') {
            return;
        }

        $header = $request->getHeaderLine('Authorization');
        $token = null;

        if (!empty($header)) {
            if (preg_match('/Bearer\s(\S+)/', $header, $matches)) {
                $token = $matches[1];
            }
        }

        if (is_null($token) || empty($token)) {
            $response = Services::response();
            $response->setJSON(['status' => 401, 'error' => 'Access denied. Token is missing.']);
            $response->setStatusCode(401);
            return $response;
        }

        $secretKey = env('JWT_SECRET', 'i-am-inevitable');
        $decoded = \App\Libraries\Jwt::decode($token, $secretKey);

        if (!$decoded || !isset($decoded->uid)) {
            $response = Services::response();
            $response->setJSON(['status' => 'error', 'message' => 'Access denied. Invalid or expired token.']);
            $response->setStatusCode(401);
            return $response;
        }

        // Inject the user ID into the request for controllers to use
        $request->api_user_id = $decoded->uid;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing here
    }
}
