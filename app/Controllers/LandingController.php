<?php

namespace App\Controllers;

use CodeIgniter\Controller;

final class LandingController extends Controller
{
    /**
     * Neutral landing endpoint.
     *
     * Purpose:
     * - Explicit placeholder
     * - No tenant assumptions
     * - No redirects
     * - No business logic
     *
     * This will be expanded in a future phase.
     */
    public function index()
    {
        return response()
            ->setStatusCode(200)
            ->setBody('CMS runtime online.');
    }
}
