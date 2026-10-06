<?php

namespace App\Controllers\Api\V1;

use CodeIgniter\RESTful\ResourceController;

class BaseController extends ResourceController
{
    protected $format = 'json';
    
    /**
     * Standardized successful JSON response.
     *
     * Supports both calling styles:
     * - sendResponse($data, 201, 'Created')
     * - sendResponse($data, 'Created')
     *
     * @param mixed $data The data to return
     * @param int|string $statusOrMessage HTTP status code OR success message
     * @param string $message Optional success message
     */
    protected function sendResponse($data, $statusOrMessage = 200, string $message = 'Success')
    {
        $status = 200;
        $finalMessage = $message;

        if (is_int($statusOrMessage)) {
            $status = $statusOrMessage;
        } elseif (is_string($statusOrMessage)) {
            $finalMessage = $statusOrMessage;
        }

        return $this->respond([
            'status'  => 'success',
            'message' => $finalMessage,
            'data'    => $data
        ], $status);
    }
    
    /**
     * Standardized error JSON response
     * @param string|array $message Error message or array of messages
     * @param int $status HTTP Status code
     */
    protected function sendError($message, $status = 400, $errors = null)
    {
        $payload = [
            'status'  => 'error',
            'message' => $message
        ];

        if ($errors !== null) {
            $payload['errors'] = $errors;
        }

        return $this->respond($payload, $status);
    }
}
