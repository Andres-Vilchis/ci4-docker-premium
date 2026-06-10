<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Services;
use App\Services\TenantContextService;

class TenantRateLimitFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $tenantId = TenantContextService::get();
        $key = 'rate_limit_tenant_' . $tenantId . '_' . $request->getIPAddress();

        $cache = Services::cache();

        $hits = $cache->get($key) ?? 0;

        if ($hits > 120) {
            return service('response')
                ->setStatusCode(429)
                ->setBody('Too many requests (tenant limit)');
        }

        $cache->save($key, $hits + 1, 60);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}