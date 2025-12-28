<?php

namespace App\Models;

use CodeIgniter\Model;

class TenantUserModel extends Model
{
    protected $table = 'tenant_users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['tenant_id', 'user_id', 'role', 'permissions'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $validationRules = [
        'tenant_id' => 'required|is_not_unique[tenants.id]',
        'user_id' => 'required|is_not_unique[users.id]',
        'role' => 'required|max_length[50]',
    ];

    /**
     * Get user's tenant membership
     */
    public function getUserTenant(int $userId, int $tenantId)
    {
        return $this->where('user_id', $userId)
                   ->where('tenant_id', $tenantId)
                   ->first();
    }

    /**
     * Get all tenants for a user
     */
    public function getUserTenants(int $userId)
    {
        return $this->select('tenants.*, tenant_users.role, tenant_users.permissions')
                   ->join('tenants', 'tenants.id = tenant_users.tenant_id')
                   ->where('tenant_users.user_id', $userId)
                   ->findAll();
    }

    /**
     * Check if user is admin of a tenant
     */
    public function isTenantAdmin(int $userId, int $tenantId): bool
    {
        $membership = $this->where('user_id', $userId)
                          ->where('tenant_id', $tenantId)
                          ->first();

        return $membership && $membership['role'] === 'admin';
    }
}
