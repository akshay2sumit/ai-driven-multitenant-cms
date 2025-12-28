<?php

namespace App\Governance\Guards;

use CodeIgniter\Exceptions\RuntimeException;

class TenantGovernanceGuard
{
    /**
     * Verify that tenant context is present
     * 
     * @throws RuntimeException If tenant context is missing
     */
    public static function ensureTenantContext(): void
    {
        // Get the tenant from the request URI
        $uri = service('request')->uri->getPath();
        
        // Check if the URI follows the /t/{tenant} pattern
        if (!preg_match('#^/t/([^/]+)#', $uri, $matches)) {
            throw new RuntimeException(
                'Tenant context is required but missing. ' .
                'Access routes must be prefixed with /t/{tenant}/.'
            );
        }
    }
}
