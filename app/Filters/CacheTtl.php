<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class CacheTtl implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $ttl = filter_var($arguments[0] ?? null, FILTER_VALIDATE_INT);

        if ($ttl !== false && $ttl > 0) {
            service('responsecache')->setTtl($ttl);
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}