<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Services\TenantContextService;

class TenantFilter implements FilterInterface
{
    /**
     * Rutas públicas reales del sistema auth
     */
    private array $publicRoutes = [
        'login',
        'register',
        'logout',
        'auth',
    ];

    public function before(RequestInterface $request, $arguments = null)
    {
        $path = trim($request->getUri()->getPath(), '/');

        foreach ($this->publicRoutes as $route) {
            if ($path === $route || str_starts_with($path, $route . '/')) {
                return null;
            }
        }

        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $tenantId = session()->get('active_organization_id');

        if (!$tenantId) {
            return redirect()->to('/post-login');
        }

        TenantContextService::set($tenantId);

        $user = auth()->user();

        if (!$user) {
            return redirect()->to('/login');
        }

        return null;
    }

    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {
        if (PHP_SAPI === 'cli') {
            TenantContextService::clear();
        }
    }
}
