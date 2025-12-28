<?php

namespace App\Contracts;

interface ReadOnlyPublishingRepositoryInterface
{
    /**
     * Get the latest published version of an entity (read-only)
     * 
     * This method is safe for runtime use as it only allows read operations
     * and enforces tenant isolation.
     * 
     * @param string $entityType The type of entity
     * @param int $entityId The ID of the entity
     * @param string $tenantId The ID of the tenant
     * @return array|null The published entity data or null if not found
     * 
     * @throws \RuntimeException If the query fails
     */
    public function getPublishedVersion(string $entityType, int $entityId, string $tenantId): ?array;
}
