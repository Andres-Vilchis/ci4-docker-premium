<?php

namespace App\Controllers\Admin;

use App\Libraries\SystemHealth;
use CodeIgniter\Controller;

class HealthController extends Controller
{
    public function index()
    {
        $data = SystemHealth::check();

        return view('admin/health', [
            'data' => $data
        ]);
    }
}