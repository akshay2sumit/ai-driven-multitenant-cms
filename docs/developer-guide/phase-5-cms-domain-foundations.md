# Phase 5: CMS Domain Foundations

## Purpose
This document explains the Phase 5 CMS domain foundations implementation for the AIBOS Multi-Tenant CMS.

## Phase 5 Constraints
**CRITICAL**: Phase 5 is strictly limited to CMS domain foundations only.

### ✅ Allowed in Phase 5
- Domain entities and value objects (Pages, Posts, Content, Media)
- Lifecycle/status enums (definitions only)
- Repository interfaces (no queries)
- Service skeletons (inactive)
- Authoring vs runtime domain boundaries

### 🚫 Forbidden in Phase 5
- Controllers, routes, or UI
- CRUD or persistence behavior
- Rendering or publishing execution
- Public APIs
- Seed/demo content
- AI content behavior

## CMS Domain Foundations

### Domain Value Objects

#### ContentStatus (`app/Cms/Domain/ValueObjects/ContentStatus.php`)
- **Purpose**: Provide type-safe content status definitions
- **Key Constants**: DRAFT, PUBLISHED, ARCHIVED, REVIEW
- **Context Validation**: Authoring vs runtime status validity
- **Security**: Status transitions must be explicitly controlled

### Domain Entities

#### BaseContentEntity (`app/Cms/Domain/Entities/BaseContentEntity.php`)
- **Purpose**: Provide foundation for content entities with tenant binding
- **Key Properties**: tenantId, title, slug, content, status, timestamps
- **Features**: Tenant isolation, status validation, metadata support
- **Security**: All content entities are tenant-scoped

#### Page Entity (`app/Cms/Domain/Entities/Page.php`)
- **Purpose**: Define page-specific content entity with page semantics
- **Key Properties**: template, metaTitle, metaDescription, parentId, sortOrder
- **Features**: SEO metadata, hierarchical structure, homepage detection
- **Security**: Pages are tenant-scoped with visibility controls

#### Media Entity (`app/Cms/Domain/Entities/Media.php`)
- **Purpose**: Define media-specific content entity with file semantics
- **Key Properties**: filename, filePath, mimeType, fileSize, dimensions
- **Features**: File type detection, accessibility support, storage tracking
- **Security**: Media files are tenant-scoped with access controls

### Repository Interfaces

#### PageRepositoryInterface (`app/Cms/Repositories/PageRepositoryInterface.php`)
- **Purpose**: Define page repository operations without implementation
- **Key Methods**: findById, findBySlug, findHomepage, findAll, save, delete
- **Features**: Tenant isolation, hierarchy support, search capabilities
- **Security**: All repository operations must be tenant-scoped

#### MediaRepositoryInterface (`app/Cms/Repositories/MediaRepositoryInterface.php`)
- **Purpose**: Define media repository operations without implementation
- **Key Methods**: findById, findByFilename, findByMimeType, save, delete
- **Features**: File type filtering, storage tracking, search capabilities
- **Security**: All repository operations must be tenant-scoped

### Service Skeletons

#### PageService (`app/Cms/Services/PageService.php`)
- **Purpose**: Provide page management framework foundation
- **Features**: Service skeleton with fail-closed operations
- **Methods**: createPage, updatePage, publishPage, generateSlug (placeholders)
- **Security**: All page operations must be tenant-scoped

#### MediaService (`app/Cms/Services/MediaService.php`)
- **Purpose**: Provide media management framework foundation
- **Features**: Service skeleton with fail-closed operations
- **Methods**: uploadMedia, processMedia, generateThumbnail, searchMedia (placeholders)
- **Security**: All media operations must be tenant-scoped

## Domain Boundaries

### Authoring vs Runtime Separation

#### Authoring Context
- **Valid Statuses**: All statuses (DRAFT, PUBLISHED, ARCHIVED, REVIEW)
- **Operations**: Content creation, editing, management
- **Visibility**: All non-deleted content accessible
- **Security**: Requires authentication and authorization

#### Runtime Context
- **Valid Statuses**: PUBLISHED only
- **Operations**: Content display and navigation
- **Visibility**: Only published content visible
- **Security**: Public access with tenant isolation

### Content Lifecycle

#### Status Definitions
- **DRAFT**: Content in draft state, not published
- **PUBLISHED**: Content published and publicly accessible
- **ARCHIVED**: Content archived and not publicly accessible
- **REVIEW**: Content under review for publication

#### Context Validation
- **Authoring**: All statuses valid for content management
- **Runtime**: Only PUBLISHED status valid for public display
- **Transitions**: Status changes must be explicitly controlled

## Database Schema Verification

### Existing Tables Validation
- **pages**: Core page table with tenant isolation and status management
- **media**: Media table with file metadata and tenant scoping
- **Foreign Keys**: Proper referential integrity with tenants table
- **Constraints**: Unique constraints prevent tenant conflicts

### Schema Compliance
- ✅ Tenant isolation enforced through tenant_id columns
- ✅ Content status management with proper constraints
- ✅ File metadata storage for media management
- ✅ Hierarchical page structure support

## Why This Approach?

1. **Domain-Driven Design**: Clear separation of domain concerns
2. **Tenant Isolation**: All entities are tenant-scoped by design
3. **Type Safety**: Value objects provide compile-time safety
4. **Future-Ready**: Structure supports Phase 6+ content management
5. **Security**: Fail-closed design prevents unauthorized access

## Next Phase Readiness

The system is now ready for Phase 6 implementation:
- Content management operations (CRUD)
- Repository implementations
- Service activation
- Publishing workflows

## Testing Strategy

### Domain Testing
- **Strategy Declaration**: Unit tests for entities and value objects
- **Deferred Justification**: Domain framework not executable in Phase 5
- **Future Implementation**: Integration tests in Phase 6+

### Repository Testing
- **Strategy Declaration**: Interface compliance tests
- **Deferred Justification**: Repository implementations not available
- **Future Implementation**: Database integration tests in Phase 6+

## Phase 5 Limitations

### Current Limitations
- No actual content management operations
- No database persistence logic
- No file handling or processing
- No publishing workflows

### Intentional Gaps
These gaps are intentional and will be addressed in future phases:
- Phase 6: Content management operations and repository implementations
- Phase 7: Publishing workflows and content lifecycle management
- Phase 8: File handling and media processing

---

*This document will be updated as phases complete.*
