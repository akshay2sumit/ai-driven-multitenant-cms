<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\TenantUserModel;
use CodeIgniter\Controller;

class AuthController extends Controller
{
    protected $userModel;
    protected $tenantUserModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->tenantUserModel = new TenantUserModel();
        $this->session = \Config\Services::session();
        helper(['form', 'url']);
    }

    /**
     * Show login form
     */
    public function login()
    {
        $tenant = $this->request->uri->getSegment(2);
        
        if ($this->session->has('user_id') && $this->session->get('tenant_name') === $tenant) {
            return redirect()->to("/t/{$tenant}");
        }

        return view('auth/login', ['tenant' => $tenant]);
    }

    /**
     * Process login
     */
    public function attemptLogin()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember') ? true : false;

        $user = $this->userModel->verifyCredentials($email, $password);

        if (!$user) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Invalid email or password');
        }

        // Get user's tenants
        $tenants = $this->tenantUserModel->getUserTenants($user['id']);
        
        if (empty($tenants)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'No tenant access found for this user');
        }

        // Validate user has access to the requested tenant
        $requestedTenant = $this->request->uri->getSegment(2);
        $tenant = null;
        
        foreach ($tenants as $t) {
            if ($t['name'] === $requestedTenant) {
                $tenant = $t;
                break;
            }
        }
        
        if (!$tenant) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Access denied to this tenant');
        }

        // Set minimal session data
        $this->session->set([
            'user_id' => $user['id'],
            'email' => $user['email'],
            'tenant_id' => $tenant['id'],
            'tenant_name' => $tenant['name'],
            'is_admin' => $tenant['role'] === 'admin',
            'logged_in' => true,
        ]);

        // Update last login
        $this->userModel->updateLastLogin($user['id']);

        return redirect()->to("/t/{$tenant['name']}")->with('message', 'Login successful');
    }

    /**
     * Logout
     */
    public function logout()
    {
        $tenant = $this->session->get('tenant_name');
        
        // Clear session data
        $this->session->destroy();
        
        return redirect()->to("/t/{$tenant}/login")->with('message', 'You have been logged out');
    }
}
