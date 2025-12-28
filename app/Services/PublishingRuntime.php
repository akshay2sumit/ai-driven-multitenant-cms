<?php

namespace App\Services;

use CodeIgniter\I18n\Time;

class PublishingRuntime
{
    /**
     * Evaluates if content is publishable based on its metadata
     * 
     * @param array $contentMetadata {
     *     @var string $tenant_id       Required. The tenant identifier
     *     @var string $current_state   Required. Current state of the content (draft|scheduled|published|archived)
     *     @var string $publish_at      Optional. ISO 8601 datetime string for scheduled publishing
     *     @var string $expire_at       Optional. ISO 8601 datetime string for content expiration
     * }
     * @return array{
     *     is_publishable: bool,
     *     reason: string,
     *     resolved_state: string
     * }
     */
    public function evaluatePublishability(array $contentMetadata): array
    {
        try {
            // Validate required fields
            if (empty($contentMetadata['tenant_id'])) {
                throw new \InvalidArgumentException('Tenant ID is required');
            }
            
            $currentState = strtolower($contentMetadata['current_state'] ?? 'draft');
            
            // State machine for publishability
            switch ($currentState) {
                case 'draft':
                    return [
                        'is_publishable' => false,
                        'reason' => 'Content is in draft state',
                        'resolved_state' => 'draft'
                    ];
                    
                case 'scheduled':
                    $publishAt = $contentMetadata['publish_at'] ?? null;
                    if (!$publishAt) {
                        return [
                            'is_publishable' => false,
                            'reason' => 'Scheduled content requires publish_at datetime',
                            'resolved_state' => 'invalid_schedule'
                        ];
                    }
                    
                    $publishTime = strtotime($publishAt);
                    if ($publishTime === false) {
                        return [
                            'is_publishable' => false,
                            'reason' => 'Invalid publish_at datetime format',
                            'resolved_state' => 'invalid_datetime'
                        ];
                    }
                    
                    if ($publishTime > time()) {
                        return [
                            'is_publishable' => false,
                            'reason' => 'Content is scheduled for future publishing',
                            'resolved_state' => 'scheduled_future'
                        ];
                    }
                    break;
                    
                case 'published':
                    // Continue to expiration check
                    break;
                    
                case 'archived':
                    return [
                        'is_publishable' => false,
                        'reason' => 'Content is archived',
                        'resolved_state' => 'archived'
                    ];
                    
                default:
                    return [
                        'is_publishable' => false,
                        'reason' => 'Unknown content state: ' . $currentState,
                        'resolved_state' => 'unknown_state'
                    ];
            }

            // Check expiration if applicable
            if (!empty($contentMetadata['expire_at'])) {
                $expireTime = strtotime($contentMetadata['expire_at']);
                if ($expireTime === false) {
                    return [
                        'is_publishable' => false,
                        'reason' => 'Invalid expire_at datetime format',
                        'resolved_state' => 'invalid_datetime'
                    ];
                }
                
                if ($expireTime < time()) {
                    return [
                        'is_publishable' => false,
                        'reason' => 'Content has expired',
                        'resolved_state' => 'expired'
                    ];
                }
            }

            return [
                'is_publishable' => true,
                'reason' => 'Content is publishable',
                'resolved_state' => 'publishable'
            ];

        } catch (\Throwable $e) {
            log_message('error', sprintf(
                'Publishing evaluation failed: %s',
                $e->getMessage()
            ));
            
            return [
                'is_publishable' => false,
                'reason' => 'Publishing evaluation failed: ' . $e->getMessage(),
                'resolved_state' => 'error'
            ];
        }
    }
}
