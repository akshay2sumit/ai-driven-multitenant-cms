<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    public function index(string $tenant)
    {
        return redirect()->to("/t/{$tenant}/pages");
    }
}
