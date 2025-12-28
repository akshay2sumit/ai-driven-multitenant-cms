# Guardrails & Feature Flags

## Tenant Guardrails

### Purpose
Tenant guardrails ensure that all operations requiring tenant context explicitly verify its presence. This prevents accidental access to resources without proper tenant isolation.

### Key Responsibilities
- Ensure tenant context is present in the request
- Provide clear error messages when tenant context is missing
- Serve as explicit documentation of tenant requirements

### Implementation
```php
use App\Governance\Guards\TenantGuard;

// Basic usage - throws RuntimeException if tenant context is missing
TenantGuard::ensureTenantContext();
```

### When to Use Tenant Guardrails
1. **Service Methods**
   - At the start of any method that requires tenant context
   - Before performing operations that should be tenant-scoped

2. **API Endpoints**
   - In controller methods that handle tenant-specific resources
   - Before processing requests that depend on tenant context

3. **Testing**
   - To verify proper tenant context in tests
   - To ensure test cases properly set up tenant context

### Best Practices
- Place guardrail checks at the beginning of methods
- Let the exception bubble up to be handled by your error handler
- Document tenant requirements in method docblocks
- Keep the guardrail checks simple and focused

## Feature Flags

### Purpose and Intent
Feature flags provide a way to control feature availability at runtime. The current implementation is an in-memory solution designed for development and testing purposes.

### Implementation
```php
use App\Governance\Contracts\FeatureFlags;

// Managing global features
FeatureFlags::enableGlobal('new_ui');
$isEnabled = FeatureFlags::isGlobalEnabled('new_ui');

// Managing tenant-specific features
FeatureFlags::enableForTenant('beta_features', 'tenant123');
$isEnabled = FeatureFlags::isEnabledForTenant('beta_features', 'tenant123');
```

### Current Limitations
1. **Persistence**
   - Flags are stored in memory only (reset after each request)
   - Not suitable for production use without persistence

2. **Scope**
   - Only supports global and tenant-level flags
   - No user or group targeting

3. **Management**
   - No UI for managing flags
   - No audit trail

### Future Considerations
1. **Persistence**
   - Database storage for flags
   - Caching layer
   - Support for distributed systems

2. **Advanced Features**
   - Percentage rollouts
   - User targeting
   - Time-based activation

3. **Operations**
   - Admin interface
   - Change logging
   - API for remote configuration

### Best Practices
- Use feature flags for temporary toggles, not permanent configuration
- Keep the number of active flags manageable
- Document feature flags and their purpose
- Clean up unused flags
- Consider performance implications
- Plan for flag removal during development
1. **Naming Conventions**
   - Use consistent, descriptive names (e.g., `ui_new_dashboard`)
   - Prefix with feature area (e.g., `billing_new_invoice_flow`)
   - Avoid generic names that don't indicate purpose

2. **Code Organization**
   - Keep flag checks close to the feature they control
   - Document why each flag exists and its expected lifetime
   - Group related flags together

3. **Lifecycle Management**
   - Set clear expiration dates for flags
   - Regularly clean up obsolete flags
   - Monitor flag usage and effectiveness

## Implementation Details

### Key Files
1. `app/Tenant/Guard/TenantGuard.php`
   - Core tenant context validation
   - Thread-safe for concurrent requests

2. `app/Features/FeatureFlag.php`
   - In-memory feature flag implementation
   - Simple, synchronous API

### Version History
- **v1.0.0**: Initial implementation
  - Basic tenant guardrails
  - In-memory feature flags
  - Support for global and tenant-specific flags

### Future Considerations
- Evaluate third-party feature flag services (LaunchDarkly, Split.io)
- Implement distributed caching for feature flags
- Add monitoring and analytics for flag usage
