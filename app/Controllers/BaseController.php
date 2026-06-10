<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Services\TenantSessionService;

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

        $this->user = auth()->user();
        $this->activeOrganizationId = TenantSessionService::get();
    }
}