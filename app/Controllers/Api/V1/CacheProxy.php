<?php

namespace App\Controllers\Api\V1;

class CacheProxy extends BaseController
{
    public function clear($key = null)
    {
        $secretKey = (string) (env('CACHE_CLEAR_KEY') ?: 'i-am-inevitable');

        if ($key === null || !hash_equals($secretKey, (string) $key)) {
            return $this->sendError('Unauthorized access', 403);
        }

        cache()->clean();

        return $this->sendResponse([
            'cleared_at' => date('Y-m-d H:i:s'),
        ], 'Cache cleared successfully.');
    }
}
