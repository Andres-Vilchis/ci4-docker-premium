<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Services\TenantKernel;
use App\Observability\Context\RequestContext;

class ApiTenantAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!function_exists('auth') || !auth()->loggedIn()) {
            return service('response')->setStatusCode(401);
        }

        TenantKernel::boot();

        // sync observability
        $user = auth()->user();
        RequestContext::$userId = $user->id ?? null;

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
