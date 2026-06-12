<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Services\TenantSessionService;
use App\Observability\Context\RequestContext;

abstract class BaseController extends Controller
{
    protected $user;
    protected ?int $activeOrganizationId = null;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        if (function_exists('auth') && auth()->loggedIn()) {
            $this->user = auth()->user();
        } else {
            $this->user = null;
        }

        $this->activeOrganizationId = TenantSessionService::get();
        RequestContext::$tenantId = $this->activeOrganizationId;
    }
}
