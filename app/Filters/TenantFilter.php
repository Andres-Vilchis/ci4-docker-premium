<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Services\TenantContextService;
use App\Services\TenantSessionService;

class TenantFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $uri = '/' . trim($request->getUri()->getPath(), '/');

        $publicRoutes = [
            '/',
            'health',
            'login',
            'register',
        ];

        // FIX: evitar ejecución innecesaria
        if (in_array(trim($uri, '/'), $publicRoutes, true)) {
            return null;
        }

        if (!function_exists('auth')) {
            return null;
        }

        $auth = auth();

        if (!$auth || !$auth->loggedIn()) {
            return redirect()->to('/login');
        }

        $orgId = TenantSessionService::get();

        if (!$orgId) {
            return redirect()->to('/post-login');
        }

        TenantContextService::boot($orgId, $auth->user());

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // no-op
    }
}