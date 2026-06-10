<?php

namespace App\Controllers;

use App\Libraries\SystemHealth;
use CodeIgniter\RESTful\ResourceController;

class Health extends ResourceController
{
    public function index()
    {
        $data = SystemHealth::check();

        $statusCode = $data['status'] === 'healthy' ? 200 : 503;

        return $this->response
            ->setStatusCode($statusCode)
            ->setJSON($data);
    }
}