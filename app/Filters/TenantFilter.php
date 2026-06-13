<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class TenantFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $path = trim($request->getUri()->getPath(), '/');

        $publicRoutes = ['', 'health', 'login', 'register', 'post-login'];

        if (in_array($path, $publicRoutes, true)) {
            return null;
        }

        $auth = auth();

        if (!$auth || !$auth->loggedIn()) {
            return redirect()->to('/login');
        }

        $orgId = session()->get('active_organization_id');

        if (!$orgId) {
            return redirect()->to('/post-login');
        }

        service('tenantContext')
            ->setTenantId((int) $orgId)
            ->setUser($auth->user());

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        service('tenantContext')->clear();
    }
}