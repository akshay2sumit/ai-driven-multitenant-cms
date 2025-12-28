<?php

namespace App\Controllers;

use App\Models\PageModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use App\Tenant\Guard\TenantGuard;

class PagesController extends BaseController
{
    protected $pageModel;

    public function __construct()
    {
        $this->pageModel = new PageModel();
    }

    /**
     * Display a listing of pages
     */
    public function index()
    {
        $data = [
            'pages' => $this->pageModel->getAllForTenant()
        ];

        return view('pages/index', $data);
    }

    /**
     * Show the form for creating a new page
     */
    public function new()
    {
        return view('pages/create');
    }

    /**
     * Store a newly created page
     */
    public function create()
    {
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'content' => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
        ];

        if ($this->pageModel->save($data)) {
            return redirect()->to("/t/" . TenantContext::require() . "/pages")
                           ->with('message', 'Page created successfully');
        }

        return redirect()->back()->withInput()->with('errors', $this->pageModel->errors());
    }

    /**
     * Show the form for editing the specified page
     */
    public function edit($id = null)
    {
        $page = $this->pageModel->findForTenant($id);
        
        if (!$page) {
            throw new PageNotFoundException('Page not found');
        }

        return view('pages/edit', ['page' => $page]);
    }

    /**
     * Update the specified page
     */
    public function update($id = null)
    {
        $page = $this->pageModel->findForTenant($id);
        
        if (!$page) {
            throw new PageNotFoundException('Page not found');
        }

        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'content' => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'id' => $id,
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
        ];

        if ($this->pageModel->save($data)) {
            return redirect()->to("/t/" . TenantContext::require() . "/pages")
                           ->with('message', 'Page updated successfully');
        }

        return redirect()->back()->withInput()->with('errors', $this->pageModel->errors());
    }

    /**
     * Remove the specified page
     */
    public function delete($id = null)
    {
        $page = $this->pageModel->findForTenant($id);
        
        if (!$page) {
            throw new PageNotFoundException('Page not found');
        }

        if ($this->pageModel->delete($id)) {
            return redirect()->to("/t/" . TenantContext::require() . "/pages")
                           ->with('message', 'Page deleted successfully');
        }

        return redirect()->back()->with('errors', ['Failed to delete page']);
    }
}
