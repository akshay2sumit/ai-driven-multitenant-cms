<?php

namespace App\Cms\Domain\Entities;

/**
 * Media Entity
 * 
 * Represents a media file in the AIBOS Multi-Tenant CMS.
 * 
 * PURPOSE: Define media-specific content entity with file semantics
 * PHASE: Execution Phase 5 — CMS Domain Foundations
 * 
 * NOTE: This entity defines media structure only. No actual file handling,
 * upload, or processing is allowed in Phase 5. This entity exists only to
 * establish the media foundation for future phases.
 * 
 * SECURITY: Media files are tenant-scoped. No cross-tenant media access is
 * allowed. File access must respect tenant boundaries and permissions.
 * 
 * @package App\Cms\Domain\Entities
 */
class Media extends BaseContentEntity
{
    /**
     * Media filename
     * 
     * NOTE: Original filename of the media file.
     * Used for display and download.
     * 
     * @var string
     */
    protected $filename;
    
    /**
     * Media file path
     * 
     * NOTE: Storage path for the media file.
     * Must be tenant-scoped.
     * 
     * @var string
     */
    protected $filePath;
    
    /**
     * Media MIME type
     * 
     * NOTE: MIME type of the media file.
     * Used for content type detection and validation.
     * 
     * @var string
     */
    protected $mimeType;
    
    /**
     * Media file size
     * 
     * NOTE: File size in bytes.
     * Used for storage management and validation.
     * 
     * @var int
     */
    protected $fileSize;
    
    /**
     * Media alt text
     * 
     * NOTE: Alternative text for accessibility.
     * Used for image alt attributes.
     * 
     * @var string|null
     */
    protected $altText;
    
    /**
     * Media width
     * 
     * NOTE: Width in pixels for image media.
     * Null for non-image media.
     * 
     * @var int|null
     */
    protected $width;
    
    /**
     * Media height
     * 
     * NOTE: Height in pixels for image media.
     * Null for non-image media.
     * 
     * @var int|null
     */
    protected $height;
    
    /**
     * Media constructor
     * 
     * NOTE: Creates a new media entity with tenant binding.
     * Media-specific properties are initialized with defaults.
     * 
     * @param int $tenantId
     * @param string $filename
     * @param string $filePath
     * @param string $mimeType
     * @param int $fileSize
     */
    public function __construct(int $tenantId, string $filename, string $filePath, string $mimeType, int $fileSize)
    {
        // Media uses filename as title and slug by default
        parent::__construct($tenantId, $filename, $filename, ContentStatus::DRAFT);
        
        $this->filename = $filename;
        $this->filePath = $filePath;
        $this->mimeType = $mimeType;
        $this->fileSize = $fileSize;
        $this->altText = null;
        $this->width = null;
        $this->height = null;
    }
    
    /**
     * Get media filename
     * 
     * NOTE: Returns original filename of the media file.
     * Used for display and download.
     * 
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }
    
    /**
     * Get media file path
     * 
     * NOTE: Returns storage path for the media file.
     * Must be tenant-scoped.
     * 
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->filePath;
    }
    
    /**
     * Get media MIME type
     * 
     * NOTE: Returns MIME type of the media file.
     * Used for content type detection and validation.
     * 
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }
    
    /**
     * Get media file size
     * 
     * NOTE: Returns file size in bytes.
     * Used for storage management and validation.
     * 
     * @return int
     */
    public function getFileSize(): int
    {
        return $this->fileSize;
    }
    
    /**
     * Get media alt text
     * 
     * NOTE: Returns alternative text for accessibility.
     * Used for image alt attributes.
     * 
     * @return string|null
     */
    public function getAltText(): ?string
    {
        return $this->altText;
    }
    
    /**
     * Get media width
     * 
     * NOTE: Returns width in pixels for image media.
     * Null for non-image media.
     * 
     * @return int|null
     */
    public function getWidth(): ?int
    {
        return $this->width;
    }
    
    /**
     * Get media height
     * 
     * NOTE: Returns height in pixels for image media.
     * Null for non-image media.
     * 
     * @return int|null
     */
    public function getHeight(): ?int
    {
        return $this->height;
    }
    
    /**
     * Check if media is an image
     * 
     * NOTE: Returns whether media is an image file.
     * Based on MIME type detection.
     * 
     * @return bool
     */
    public function isImage(): bool
    {
        return strpos($this->mimeType, 'image/') === 0;
    }
    
    /**
     * Check if media is a video
     * 
     * NOTE: Returns whether media is a video file.
     * Based on MIME type detection.
     * 
     * @return bool
     */
    public function isVideo(): bool
    {
        return strpos($this->mimeType, 'video/') === 0;
    }
    
    /**
     * Check if media is an audio file
     * 
     * NOTE: Returns whether media is an audio file.
     * Based on MIME type detection.
     * 
     * @return bool
     */
    public function isAudio(): bool
    {
        return strpos($this->mimeType, 'audio/') === 0;
    }
    
    /**
     * Check if media is a document
     * 
     * NOTE: Returns whether media is a document file.
     * Based on MIME type detection.
     * 
     * @return bool
     */
    public function isDocument(): bool
    {
        $documentTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'text/plain',
            'text/csv',
        ];
        
        return in_array($this->mimeType, $documentTypes);
    }
    
    /**
     * Check if media has dimensions
     * 
     * NOTE: Returns whether media has width and height.
     * Typically true for images and videos.
     * 
     * @return bool
     */
    public function hasDimensions(): bool
    {
        return $this->width !== null && $this->height !== null;
    }
    
    /**
     * Check if media has alt text
     * 
     * NOTE: Returns whether media has alternative text.
     * Used for accessibility compliance.
     * 
     * @return bool
     */
    public function hasAltText(): bool
    {
        return !empty($this->altText);
    }
    
    /**
     * Get file size in human readable format
     * 
     * NOTE: Returns file size formatted for display.
     * Uses appropriate units (B, KB, MB, GB).
     * 
     * @return string
     */
    public function getFormattedFileSize(): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->fileSize;
        $unitIndex = 0;
        
        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }
        
        return round($size, 2) . ' ' . $units[$unitIndex];
    }
    
    /**
     * Get file extension
     * 
     * NOTE: Returns file extension from filename.
     * Used for file type detection.
     * 
     * @return string
     */
    public function getFileExtension(): string
    {
        return pathinfo($this->filename, PATHINFO_EXTENSION);
    }
    
    /**
     * Validate media structure
     * 
     * NOTE: Validates media meets structural requirements.
     * Ensures required properties and security constraints.
     * 
     * @return bool
     */
    public function validate(): bool
    {
        // Phase 5: Basic structural validation
        // Future phases will add media-specific validation
        
        // Base content validation
        if (!parent::validate()) {
            return false;
        }
        
        // Filename must not be empty
        if (empty($this->filename)) {
            return false;
        }
        
        // File path must not be empty
        if (empty($this->filePath)) {
            return false;
        }
        
        // MIME type must not be empty
        if (empty($this->mimeType)) {
            return false;
        }
        
        // File size must be positive
        if ($this->fileSize <= 0) {
            return false;
        }
        
        // Dimensions must be positive if set
        if ($this->width !== null && $this->width <= 0) {
            return false;
        }
        
        if ($this->height !== null && $this->height <= 0) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Set media alt text
     * 
     * NOTE: Sets alternative text for accessibility.
     * Used in future phases for accessibility management.
     * 
     * @param string|null $altText
     * @return void
     */
    public function setAltText(?string $altText): void
    {
        $this->altText = $altText;
        $this->updatedAt = new \DateTime();
    }
    
    /**
     * Set media dimensions
     * 
     * NOTE: Sets width and height for image media.
     * Used in future phases for image processing.
     * 
     * @param int|null $width
     * @param int|null $height
     * @return void
     */
    public function setDimensions(?int $width, ?int $height): void
    {
        $this->width = $width;
        $this->height = $height;
        $this->updatedAt = new \DateTime();
    }
    
    /**
     * Add metadata
     * 
     * NOTE: Adds metadata to the media.
     * Used for file processing information.
     * 
     * @param string $key
     * @param mixed $value
     * @return void
     */
    protected function addMetadata(string $key, $value): void
    {
        parent::addMetadata($key, $value);
        
        // Add common metadata as properties for convenience
        switch ($key) {
            case 'width':
                $this->width = $value;
                break;
            case 'height':
                $this->height = $value;
                break;
            case 'alt_text':
                $this->altText = $value;
                break;
        }
    }
}
