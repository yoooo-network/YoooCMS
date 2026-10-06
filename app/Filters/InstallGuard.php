<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class InstallGuard implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!is_file(WRITEPATH . 'installed.lock')) {
            return redirect()->to('/install');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
