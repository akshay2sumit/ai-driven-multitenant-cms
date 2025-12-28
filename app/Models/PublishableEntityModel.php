<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Validation\Validation;
use App\Tenant\Context as TenantContext;
use App\Tenant\Guard\TenantGuard;
use InvalidArgumentException;

class PublishableEntityModel extends Model
{
    protected $table = 'publishable_entities';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'tenant_id',
        'entity_type',
        'entity_id',
        'version',
        'state',
        'published_at',
        'published_by',
        'data'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    // Valid states for publishable entities
    public const STATE_DRAFT = 'draft';
    public const STATE_REVIEW = 'review';
    public const STATE_PUBLISHED = 'published';
    public const STATE_ARCHIVED = 'archived';
    public const STATE_RETRACTED = 'retracted';
    
    // State transition rules
    protected $allowedTransitions = [
        self::STATE_DRAFT => [self::STATE_REVIEW, self::STATE_ARCHIVED],
        self::STATE_REVIEW => [self::STATE_DRAFT, self::STATE_PUBLISHED, self::STATE_ARCHIVED],
        self::STATE_PUBLISHED => [self::STATE_ARCHIVED, self::STATE_RETRACTED],
        self::STATE_ARCHIVED => [self::STATE_DRAFT, self::STATE_RETRACTED],
        self::STATE_RETRACTED => [self::STATE_DRAFT, self::STATE_ARCHIVED],
    ];
    
    protected $validationRules = [
        'tenant_id' => 'required|is_natural_no_zero',
        'entity_type' => 'required|alpha_dash|max_length[50]',
        'entity_id' => 'required|is_natural_no_zero',
        'version' => 'required|is_natural_no_zero',
        'state' => 'required|in_list[draft,review,published,archived,retracted]',
        'data' => 'required|valid_json',
    ];
    
    protected $validationMessages = [
        'tenant_id' => [
            'required' => 'Tenant ID is required',
            'is_natural_no_zero' => 'Invalid tenant ID',
        ],
        'entity_type' => [
            'required' => 'Entity type is required',
            'alpha_dash' => 'Entity type can only contain alphanumeric characters, underscores, and dashes',
            'max_length' => 'Entity type cannot exceed 50 characters',
        ],
        'entity_id' => [
            'required' => 'Entity ID is required',
            'is_natural_no_zero' => 'Invalid entity ID',
        ],
        'state' => [
            'required' => 'State is required',
            'in_list' => 'Invalid state',
        ],
        'data' => [
            'required' => 'Data is required',
            'valid_json' => 'Data must be valid JSON',
        ],
    ];
    
    /**
     * Before insert/update hook to ensure tenant context
     */
    protected function beforeInsert(array $data): array
    {
        // Ensure tenant_id is set from context if not provided
        if (!isset($data['data']['tenant_id'])) {
            $data['data']['tenant_id'] = TenantContext::require();
        }
        
        // Ensure data is properly encoded
        if (is_array($data['data'])) {
            $data['data'] = json_encode($data['data']);
        }
        
        return $data;
    }
    
    /**
     * Get the current published version of an entity
     */
    public function getPublishedVersion(string $entityType, int $entityId): ?array
    {
        $tenantId = TenantContext::require();
        
        return $this->where([
                'tenant_id' => $tenantId,
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'state' => self::STATE_PUBLISHED,
            ])
            ->orderBy('version', 'DESC')
            ->first();
    }
    
    /**
     * Get the latest version of an entity, regardless of state
     */
    public function getLatestVersion(string $entityType, int $entityId): ?array
    {
        $tenantId = TenantContext::require();
        
        return $this->where([
                'tenant_id' => $tenantId,
                'entity_type' => $entityType,
                'entity_id' => $entityId,
            ])
            ->orderBy('version', 'DESC')
            ->first();
    }
    
    /**
     * Transition an entity to a new state
     * 
     * @throws InvalidArgumentException If the transition is not allowed
     */
    public function transitionState(int $id, string $newState, ?int $publishedBy = null): bool
    {
        $tenantId = TenantContext::require();
        $entity = $this->where('id', $id)
                      ->where('tenant_id', $tenantId)
                      ->first();
        
        if (!$entity) {
            throw new InvalidArgumentException('Entity not found');
        }
        
        $currentState = $entity['state'];
        
        // Check if transition is allowed
        if (!in_array($newState, $this->allowedTransitions[$currentState] ?? [], true)) {
            throw new InvalidArgumentException(
                sprintf('Invalid state transition from %s to %s', $currentState, $newState)
            );
        }
        
        // Prepare update data
        $updateData = ['state' => $newState];
        
        // Set published_at if transitioning to published
        if ($newState === self::STATE_PUBLISHED) {
            $updateData['published_at'] = date('Y-m-d H:i:s');
            $updateData['published_by'] = $publishedBy;
        }
        
        return $this->update($id, $updateData);
    }
    
    /**
     * Create a new version of an entity
     */
    public function createVersion(
        string $entityType,
        int $entityId,
        array $data,
        string $state = self::STATE_DRAFT,
        ?int $userId = null
    ): ?int {
        $tenantId = TenantContext::require();
        
        // Get the latest version to increment from
        $latest = $this->getLatestVersion($entityType, $entityId);
        $newVersion = $latest ? $latest['version'] + 1 : 1;
        
        // Ensure data has required fields
        $data = array_merge($data, [
            'tenant_id' => $tenantId,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'version' => $newVersion,
            'state' => $state,
            'published_by' => $state === self::STATE_PUBLISHED ? $userId : null,
            'published_at' => $state === self::STATE_PUBLISHED ? date('Y-m-d H:i:s') : null,
        ]);
        
        $id = $this->insert($data, true);
        
        return $id ? (int)$id : null;
    }
    
    /**
     * Get all versions of an entity
     */
    public function getEntityVersions(string $entityType, int $entityId): array
    {
        $tenantId = TenantContext::require();
        
        return $this->where([
                'tenant_id' => $tenantId,
                'entity_type' => $entityType,
                'entity_id' => $entityId,
            ])
            ->orderBy('version', 'ASC')
            ->findAll();
    }
    
    /**
     * Get entities by state
     */
    public function getByState(string $entityType, string $state, int $limit = 10, int $offset = 0): array
    {
        $tenantId = TenantContext::require();
        
        return $this->where([
                'tenant_id' => $tenantId,
                'entity_type' => $entityType,
                'state' => $state,
            ])
            ->orderBy('created_at', 'DESC')
            ->findAll($limit, $offset);
    }
    
    /**
     * Check if an entity has a published version
     */
    public function isPublished(string $entityType, int $entityId): bool
    {
        return $this->getPublishedVersion($entityType, $entityId) !== null;
    }
    
    /**
     * Get the current state of an entity
     */
    public function getCurrentState(string $entityType, int $entityId): ?string
    {
        $latest = $this->getLatestVersion($entityType, $entityId);
        return $latest ? $latest['state'] : null;
    }
    
    /**
     * Soft delete all versions of an entity
     */
    public function deleteEntity(string $entityType, int $entityId): bool
    {
        $tenantId = TenantContext::require();
        
        return $this->where([
            'tenant_id' => $tenantId,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
        ])->delete();
    }
}
