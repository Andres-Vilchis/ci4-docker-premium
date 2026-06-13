<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Observability\Context\RequestContext;

class ObservabilityFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // SINGLE SOURCE OF TRUTH (CRITICAL FIX)
        RequestContext::init();

        // Inject request_id into global request early
        $request->setHeader('X-Request-ID', RequestContext::$requestId);

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Ensure header always exists
        $response->setHeader('X-Request-ID', RequestContext::$requestId);

        $duration = RequestContext::elapsedMs();

        log_message('info', json_encode([
            'type' => 'http_request',
            'request_id' => RequestContext::$requestId,
            'method' => $request->getMethod(),
            'path' => $request->getUri()->getPath(),
            'status' => $response->getStatusCode(),
            'duration_ms' => $duration,
            'tenant_id' => RequestContext::$tenantId,
            'user_id' => RequestContext::$userId,
        ]));

        return null;
    }
}
