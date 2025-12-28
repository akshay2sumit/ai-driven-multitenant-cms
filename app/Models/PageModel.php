<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Tenant\Guard\TenantGuard;
use App\Tenant\Context as TenantContext;

class PageModel extends Model
{
    protected $table = 'pages';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'title', 
        'content',
        'status',
        'published_at',
        'expire_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $validationRules = [
        'title' => 'required|min_length[3]|max_length[255]',
        'content' => 'permit_empty',
        'status' => 'in_list[draft,published,archived,scheduled]',
        'published_at' => 'permit_empty|valid_date',
        'expire_at' => 'permit_empty|valid_date'
    ];
    protected $skipValidation = false;
    protected $beforeInsert = ['setTenantId', 'setDefaultStatus'];
    protected $beforeUpdate = ['ensureTenantOwnership'];
    protected $beforeDelete = ['ensureTenantOwnership'];

    /**
     * Set the tenant_id for new records
     */
    protected function setTenantId(array $data)
    {
        TenantGuard::ensureTenantContext();
        $data['data']['tenant_id'] = TenantContext::require();
        return $data;
    }

    /**
     * Set default status to draft if not provided
     */
    protected function setDefaultStatus(array $data)
    {
        if (!isset($data['data']['status'])) {
            $data['data']['status'] = 'draft';
        }
        return $data;
    }

    /**
     * Ensure the record belongs to the current tenant
     */
    protected function ensureTenantOwnership(array $data)
    {
        $id = is_array($data['id']) ? $data['id'] : [$data['id']];
        $tenantId = TenantContext::require();
        
        $count = $this->whereIn('id', $id)
                     ->where('tenant_id', $tenantId)
                     ->countAllResults();

        if ($count !== count($id)) {
            throw new \RuntimeException('Operation not permitted. One or more records do not belong to the current tenant.');
        }

        return $data;
    }

    /**
     * Find a page by ID for the current tenant
     */
    public function findForTenant($id)
    {
        TenantGuard::ensureTenantContext();
        return $this->where('id', $id)
                   ->where('tenant_id', TenantContext::require())
                   ->first();
    }

    /**
     * Get all pages for the current tenant
     */
    public function getAllForTenant()
    {
        TenantGuard::ensureTenantContext();
        return $this->where('tenant_id', TenantContext::require())
                   ->orderBy('created_at', 'DESC')
                   ->findAll();
    }
}
