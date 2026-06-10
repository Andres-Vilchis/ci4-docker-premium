<?php

namespace App\Controllers\Admin;

use App\Libraries\Queue\QueueMonitorService;
use CodeIgniter\Controller;

class QueueController extends Controller
{
    public function index()
    {
        $service = new QueueMonitorService();

        return $this->response->setJSON([
            'status' => 'ok',
            'stats'  => $service->stats(),
        ]);
    }

    public function failed()
    {
        $service = new QueueMonitorService();

        return $this->response->setJSON([
            'failed_jobs' => $service->failedJobs(),
        ]);
    }

    public function retry($payload)
    {
        $service = new QueueMonitorService();

        $result = $service->retryJob($payload);

        return $this->response->setJSON([
            'retried' => $result,
        ]);
    }
}