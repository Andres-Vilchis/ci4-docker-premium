<?php

namespace App\Controllers;

use App\Services\AuditService;

class Home extends BaseController
{
    public function index(): string
    {
        AuditService::log(
            'home.view',
            'dashboard'
        );

        return view('welcome_message');
    }
}