<?php

namespace App\Services;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\I18n\Time;

class PublishingRuntimeTest extends CIUnitTestCase
{
    protected $publishingRuntime;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->publishingRuntime = new PublishingRuntime();
    }
    
    public function testDraftContentIsNotPublishable()
    {
        $result = $this->publishingRuntime->evaluatePublishability([
            'tenant_id' => 'tenant-1',
            'current_state' => 'draft'
        ]);
        
        $this->assertFalse($result['is_publishable']);
        $this->assertSame('Content is in draft state', $result['reason']);
    }
    
    public function testScheduledFutureContentIsNotPublishable()
    {
        $future = date('c', strtotime('+1 day'));
        
        $result = $this->publishingRuntime->evaluatePublishability([
            'tenant_id' => 'tenant-1',
            'current_state' => 'scheduled',
            'publish_at' => $future
        ]);
        
        $this->assertFalse($result['is_publishable']);
        $this->assertSame('Content is scheduled for future publishing', $result['reason']);
    }
    
    public function testScheduledPastContentIsPublishable()
    {
        $past = date('c', strtotime('-1 day'));
        
        $result = $this->publishingRuntime->evaluatePublishability([
            'tenant_id' => 'tenant-1',
            'current_state' => 'scheduled',
            'publish_at' => $past
        ]);
        
        $this->assertTrue($result['is_publishable']);
        $this->assertSame('Content is publishable', $result['reason']);
    }
    
    public function testExpiredContentIsNotPublishable()
    {
        $past = date('c', strtotime('-1 day'));
        
        $result = $this->publishingRuntime->evaluatePublishability([
            'tenant_id' => 'tenant-1',
            'current_state' => 'published',
            'expire_at' => $past
        ]);
        
        $this->assertFalse($result['is_publishable']);
        $this->assertSame('Content has expired', $result['reason']);
    }
    
    public function testPublishedContentIsPublishable()
    {
        $future = date('c', strtotime('+1 day'));
        
        $result = $this->publishingRuntime->evaluatePublishability([
            'tenant_id' => 'tenant-1',
            'current_state' => 'published',
            'expire_at' => $future
        ]);
        
        $this->assertTrue($result['is_publishable']);
    }
    
    public function testArchivedContentIsNotPublishable()
    {
        $result = $this->publishingRuntime->evaluatePublishability([
            'tenant_id' => 'tenant-1',
            'current_state' => 'archived'
        ]);
        
        $this->assertFalse($result['is_publishable']);
        $this->assertSame('Content is archived', $result['reason']);
    }
    
    public function testInvalidStateIsNotPublishable()
    {
        $result = $this->publishingRuntime->evaluatePublishability([
            'tenant_id' => 'tenant-1',
            'current_state' => 'invalid_state'
        ]);
        
        $this->assertFalse($result['is_publishable']);
        $this->assertStringStartsWith('Unknown content state:', $result['reason']);
    }
    
    public function testTenantIdIsRequired()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->publishingRuntime->evaluatePublishability([
            'current_state' => 'published'
        ]);
    }
}
