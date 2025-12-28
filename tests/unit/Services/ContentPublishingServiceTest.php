<?php

namespace Tests\Unit\Services;

use App\Services\ContentPublishingService;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

class ContentPublishingServiceTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $refresh = true;
    protected $namespace = 'App';
    protected $pageModel;
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Set up tenant context for testing
        if (method_exists($this, 'setPrivateProperty')) {
            $this->setPrivateProperty(service('tenant'), 'tenant', ['id' => 1]);
        }
        
        $this->pageModel = new \App\Models\PageModel();
        $this->service = new ContentPublishingService();
    }

    public function testDraftContentIsNotPublishable()
    {
        $pageId = $this->pageModel->insert([
            'title' => 'Draft Page',
            'content' => 'Draft content',
            'status' => 'draft'
        ]);

        $result = $this->service->isPublishable($pageId);
        $this->assertFalse($result['is_publishable']);
        $this->assertSame('draft', $result['resolved_state']);
    }

    public function testPublishedContentIsPublishable()
    {
        $pageId = $this->pageModel->insert([
            'title' => 'Published Page',
            'content' => 'Published content',
            'status' => 'published'
        ]);

        $result = $this->service->isPublishable($pageId);
        $this->assertTrue($result['is_publishable']);
        $this->assertSame('publishable', $result['resolved_state']);
    }

    public function testScheduledFutureContentIsNotPublishable()
    {
        $pageId = $this->pageModel->insert([
            'title' => 'Scheduled Page',
            'content' => 'Scheduled content',
            'status' => 'scheduled',
            'published_at' => date('Y-m-d H:i:s', strtotime('+1 day'))
        ]);

        $result = $this->service->isPublishable($pageId);
        $this->assertFalse($result['is_publishable']);
        $this->assertSame('scheduled_future', $result['resolved_state']);
    }

    public function testExpiredContentIsNotPublishable()
    {
        $pageId = $this->pageModel->insert([
            'title' => 'Expired Page',
            'content' => 'Expired content',
            'status' => 'published',
            'expire_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
        ]);

        $result = $this->service->isPublishable($pageId);
        $this->assertFalse($result['is_publishable']);
        $this->assertSame('expired', $result['resolved_state']);
    }

    public function testArchivedContentIsNotPublishable()
    {
        $pageId = $this->pageModel->insert([
            'title' => 'Archived Page',
            'content' => 'Archived content',
            'status' => 'archived'
        ]);

        $result = $this->service->isPublishable($pageId);
        $this->assertFalse($result['is_publishable']);
        $this->assertSame('archived', $result['resolved_state']);
    }

    public function testNonExistentContentIsNotPublishable()
    {
        $result = $this->service->isPublishable(99999);
        $this->assertFalse($result['is_publishable']);
        $this->assertSame('not_found', $result['resolved_state']);
    }
}
