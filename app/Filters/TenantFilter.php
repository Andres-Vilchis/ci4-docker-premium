<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Services\TenantContextService;
use App\Services\TenantSessionService;
use App\Observability\Context\RequestContext;

class TenantFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $path = trim($request->getUri()->getPath(), '/');

        $publicRoutes = [
            '',
            'health',
            'login',
            'register',
            'post-login',
        ];

        if (in_array($path, $publicRoutes, true)) {
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
        RequestContext::$tenantId = $orgId;
        RequestContext::$userId = $auth->user()->id ?? null;

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}