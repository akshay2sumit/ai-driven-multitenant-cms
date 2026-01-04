<?php

namespace App\Cms\Domain\Entities;

/**
 * Page Entity
 * 
 * Represents a static page in the AIBOS Multi-Tenant CMS.
 * 
 * PURPOSE: Define page-specific content entity with page semantics
 * PHASE: Execution Phase 5 — CMS Domain Foundations
 * 
 * NOTE: This entity defines page structure only. No actual page management,
 * rendering, or navigation is allowed in Phase 5. This entity exists only to
 * establish the page foundation for future phases.
 * 
 * SECURITY: Pages are tenant-scoped content entities. No cross-tenant page
 * access is allowed. Page visibility must respect status and tenant boundaries.
 * 
 * @package App\Cms\Domain\Entities
 */
class Page extends BaseContentEntity
{
    /**
     * Page template
     * 
     * NOTE: Template used for page rendering.
     * Structure depends on template system.
     * 
     * @var string|null
     */
    protected $template;
    
    /**
     * Page meta title
     * 
     * NOTE: SEO title for the page.
     * May differ from content title.
     * 
     * @var string|null
     */
    protected $metaTitle;
    
    /**
     * Page meta description
     * 
     * NOTE: SEO description for the page.
     * Used for search engine snippets.
     * 
     * @var string|null
     */
    protected $metaDescription;
    
    /**
     * Page meta keywords
     * 
     * NOTE: SEO keywords for the page.
     * Used for search engine optimization.
     * 
     * @var array
     */
    protected $metaKeywords;
    
    /**
     * Page parent ID
     * 
     * NOTE: Parent page for hierarchical pages.
     * Null for top-level pages.
     * 
     * @var int|null
     */
    protected $parentId;
    
    /**
     * Page sort order
     * 
     * NOTE: Display order for pages.
     * Used for navigation and sorting.
     * 
     * @var int
     */
    protected $sortOrder;
    
    /**
     * Page is homepage flag
     * 
     * NOTE: Whether this page is the tenant homepage.
     * Only one page per tenant can be homepage.
     * 
     * @var bool
     */
    protected $isHomepage;
    
    /**
     * Page constructor
     * 
     * NOTE: Creates a new page entity with tenant binding.
     * Page-specific properties are initialized with defaults.
     * 
     * @param int $tenantId
     * @param string $title
     * @param string $slug
     * @param string $status
     */
    public function __construct(int $tenantId, string $title, string $slug, string $status)
    {
        parent::__construct($tenantId, $title, $slug, $status);
        
        $this->template = null;
        $this->metaTitle = null;
        $this->metaDescription = null;
        $this->metaKeywords = [];
        $this->parentId = null;
        $this->sortOrder = 0;
        $this->isHomepage = false;
    }
    
    /**
     * Get page template
     * 
     * NOTE: Returns template used for page rendering.
     * Structure depends on template system.
     * 
     * @return string|null
     */
    public function getTemplate(): ?string
    {
        return $this->template;
    }
    
    /**
     * Get page meta title
     * 
     * NOTE: Returns SEO title for the page.
     * May differ from content title.
     * 
     * @return string|null
     */
    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }
    
    /**
     * Get page meta description
     * 
     * NOTE: Returns SEO description for the page.
     * Used for search engine snippets.
     * 
     * @return string|null
     */
    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }
    
    /**
     * Get page meta keywords
     * 
     * NOTE: Returns SEO keywords for the page.
     * Used for search engine optimization.
     * 
     * @return array
     */
    public function getMetaKeywords(): array
    {
        return $this->metaKeywords;
    }
    
    /**
     * Get page parent ID
     * 
     * NOTE: Returns parent page for hierarchical pages.
     * Null for top-level pages.
     * 
     * @return int|null
     */
    public function getParentId(): ?int
    {
        return $this->parentId;
    }
    
    /**
     * Get page sort order
     * 
     * NOTE: Returns display order for pages.
     * Used for navigation and sorting.
     * 
     * @return int
     */
    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }
    
    /**
     * Check if page is homepage
     * 
     * NOTE: Returns whether this page is the tenant homepage.
     * Only one page per tenant can be homepage.
     * 
     * @return bool
     */
    public function isHomepage(): bool
    {
        return $this->isHomepage;
    }
    
    /**
     * Check if page has parent
     * 
     * NOTE: Returns whether page has a parent page.
     * Used for hierarchical navigation.
     * 
     * @return bool
     */
    public function hasParent(): bool
    {
        return $this->parentId !== null;
    }
    
    /**
     * Check if page is top-level
     * 
     * NOTE: Returns whether page is a top-level page.
     * Used for navigation structure.
     * 
     * @return bool
     */
    public function isTopLevel(): bool
    {
        return $this->parentId === null;
    }
    
    /**
     * Check if page has SEO metadata
     * 
     * NOTE: Returns whether page has SEO metadata.
     * Used for SEO optimization checks.
     * 
     * @return bool
     */
    public function hasSeoMetadata(): bool
    {
        return !empty($this->metaTitle) || !empty($this->metaDescription) || !empty($this->metaKeywords);
    }
    
    /**
     * Get effective page title
     * 
     * NOTE: Returns effective title for display.
     * Uses meta title if available, falls back to content title.
     * 
     * @return string
     */
    public function getEffectiveTitle(): string
    {
        return $this->metaTitle ?? $this->title;
    }
    
    /**
     * Validate page structure
     * 
     * NOTE: Validates page meets structural requirements.
     * Ensures required properties and security constraints.
     * 
     * @return bool
     */
    public function validate(): bool
    {
        // Phase 5: Basic structural validation
        // Future phases will add page-specific validation
        
        // Base content validation
        if (!parent::validate()) {
            return false;
        }
        
        // Sort order must be non-negative
        if ($this->sortOrder < 0) {
            return false;
        }
        
        // Parent ID must be positive if set
        if ($this->parentId !== null && $this->parentId <= 0) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Set page template
     * 
     * NOTE: Sets template used for page rendering.
     * Used in future phases for template management.
     * 
     * @param string|null $template
     * @return void
     */
    public function setTemplate(?string $template): void
    {
        $this->template = $template;
        $this->updatedAt = new \DateTime();
    }
    
    /**
     * Set page meta title
     * 
     * NOTE: Sets SEO title for the page.
     * Used in future phases for SEO management.
     * 
     * @param string|null $metaTitle
     * @return void
     */
    public function setMetaTitle(?string $metaTitle): void
    {
        $this->metaTitle = $metaTitle;
        $this->updatedAt = new \DateTime();
    }
    
    /**
     * Set page meta description
     * 
     * NOTE: Sets SEO description for the page.
     * Used in future phases for SEO management.
     * 
     * @param string|null $metaDescription
     * @return void
     */
    public function setMetaDescription(?string $metaDescription): void
    {
        $this->metaDescription = $metaDescription;
        $this->updatedAt = new \DateTime();
    }
    
    /**
     * Set page meta keywords
     * 
     * NOTE: Sets SEO keywords for the page.
     * Used in future phases for SEO management.
     * 
     * @param array $metaKeywords
     * @return void
     */
    public function setMetaKeywords(array $metaKeywords): void
    {
        $this->metaKeywords = $metaKeywords;
        $this->updatedAt = new \DateTime();
    }
    
    /**
     * Set page parent ID
     * 
     * NOTE: Sets parent page for hierarchical pages.
     * Used in future phases for hierarchy management.
     * 
     * @param int|null $parentId
     * @return void
     */
    public function setParentId(?int $parentId): void
    {
        $this->parentId = $parentId;
        $this->updatedAt = new \DateTime();
    }
    
    /**
     * Set page sort order
     * 
     * NOTE: Sets display order for pages.
     * Used in future phases for navigation management.
     * 
     * @param int $sortOrder
     * @return void
     */
    public function setSortOrder(int $sortOrder): void
    {
        $this->sortOrder = $sortOrder;
        $this->updatedAt = new \DateTime();
    }
    
    /**
     * Set homepage flag
     * 
     * NOTE: Sets whether this page is the tenant homepage.
     * Used in future phases for homepage management.
     * 
     * @param bool $isHomepage
     * @return void
     */
    public function setIsHomepage(bool $isHomepage): void
    {
        $this->isHomepage = $isHomepage;
        $this->updatedAt = new \DateTime();
    }
}
