<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class CacheController extends Controller
{
    public function clear()
    {
        cache()->clean();

        return redirect()
            ->back()
            ->with('message', 'Cache cleared successfully at ' . date('Y-m-d H:i:s'));
    }
}
