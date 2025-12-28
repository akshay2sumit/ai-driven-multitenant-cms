<?php

namespace App\Services;

use App\Models\PageModel;
use CodeIgniter\I18n\Time;

class ContentPublishingService
{
    protected $pageModel;

    public function __construct()
    {
        $this->pageModel = new PageModel();
    }

    public function isPublishable($contentId, string $contentType = 'page'): array
    {
        try {
            $content = $this->getContent($contentId, $contentType);
            
            if (!$content) {
                return [
                    'is_publishable' => false,
                    'reason' => 'Content not found',
                    'resolved_state' => 'not_found'
                ];
            }

            switch ($content['status'] ?? 'draft') {
                case 'draft':
                    return [
                        'is_publishable' => false,
                        'reason' => 'Content is in draft state',
                        'resolved_state' => 'draft'
                    ];
                    
                case 'scheduled':
                    $publishAt = $content['published_at'] ?? null;
                    if (!$publishAt || strtotime($publishAt) > time()) {
                        return [
                            'is_publishable' => false,
                            'reason' => 'Content is scheduled for future publishing',
                            'resolved_state' => 'scheduled_future'
                        ];
                    }
                    break;
                    
                case 'published':
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
                        'reason' => 'Unknown content state',
                        'resolved_state' => 'unknown_state'
                    ];
            }

            if (!empty($content['expire_at'])) {
                $expireAt = $content['expire_at'] instanceof Time 
                    ? $content['expire_at']->getTimestamp() 
                    : strtotime($content['expire_at']);
                
                if ($expireAt < time()) {
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
                'Publishing check failed for %s ID %s: %s',
                $contentType,
                $contentId,
                $e->getMessage()
            ));
            
            return [
                'is_publishable' => false,
                'reason' => 'Publishing check failed',
                'resolved_state' => 'error'
            ];
        }
    }

    protected function getContent($contentId, string $contentType)
    {
        if ($contentType === 'page') {
            return $this->pageModel->findForTenant($contentId);
        }
        
        throw new \InvalidArgumentException("Unsupported content type: {$contentType}");
    }
}
