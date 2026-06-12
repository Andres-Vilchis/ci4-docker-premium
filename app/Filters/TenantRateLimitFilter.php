<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Services\TenantSessionService;

class TenantRateLimitFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $path = '/' . trim($request->getUri()->getPath(), '/');

        $excluded = [
            '',
            '/',
            'health',
            'health/*',
        ];

        if (in_array($path, $excluded, true)) {
            return null;
        }

        $tenantId = null;

        if (class_exists(TenantSessionService::class)) {
            $tenantId = TenantSessionService::get();
        }

        if (!$tenantId) {
            return null;
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // no-op
    }
}
