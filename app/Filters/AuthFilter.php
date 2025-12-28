<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = \Config\Services::session();
        
        // Check if user is not logged in
        if (!$session->has('logged_in') || $session->get('logged_in') !== true) {
            // Save the intended URL before redirecting to login
            $session->set('redirect_url', current_url());
            
            return redirect()->to('/login')
                ->with('error', 'Please login to access this page');
        }
        
        // Check if user has access to the current tenant
        $router = \CodeIgniter\Config\Services::router();
        $uri = new \CodeIgniter\HTTP\URI(current_url());
        $path = $uri->getPath();
        
        // Extract tenant from URL (if using path-based tenancy: /t/tenant-name/...)
        if (preg_match('|^/t/([^/]+)|', $path, $matches)) {
            $tenantName = $matches[1];
            
            // Verify user has access to this tenant
            $tenantUserModel = new \App\Models\TenantUserModel();
            $userTenants = $tenantUserModel->getUserTenants($session->get('user_id'));
            
            $hasAccess = false;
            foreach ($userTenants as $tenant) {
                if ($tenant['name'] === $tenantName) {
                    $hasAccess = true;
                    
                    // Update session with current tenant
                    $session->set([
                        'tenant_id' => $tenant['id'],
                        'tenant_name' => $tenant['name'],
                        'is_admin' => $tenant['role'] === 'admin',
                    ]);
                    
                    break;
                }
            }
            
            if (!$hasAccess) {
                return redirect()->to('/dashboard')
                    ->with('error', 'You do not have access to this tenant');
            }
        }
        
        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after the request
        return $response;
    }
}
