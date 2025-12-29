<?php

namespace Tests\Unit\Services;

use App\Repositories\PublishableEntityRepository;
use App\Services\AuthoringService;
use App\Tenant\Context as TenantContext;
use CodeIgniter\Test\CIUnitTestCase;
use RuntimeException;

class AuthoringServiceTest extends CIUnitTestCase
{
    private $mockRepository;
    private $tenantId = 'test-tenant-123';
    private $entityType = 'page';
    private $entityId = 1;
    private $userId = 42;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock the repository
        $this->mockRepository = $this->createMock(PublishableEntityRepository::class);
        
        // Set up tenant context
        TenantContext::set($this->tenantId);
    }

    protected function tearDown(): void
    {
        // Clean up tenant context
        TenantContext::unset();
        
        parent::tearDown();
    }

    /**
     * Test that author role cannot perform publisher actions
     */
    public function testAuthorRoleCannotPublish()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Insufficient permissions');

        $service = new AuthoringService(
            $this->mockRepository,
            $this->tenantId,
            'author',
            $this->userId
        );

        $service->publish($this->entityType, $this->entityId);
    }

    /**
     * Test that reviewer role cannot publish
     */
    public function testReviewerRoleCannotPublish()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Insufficient permissions');

        $service = new AuthoringService(
            $this->mockRepository,
            $this->tenantId,
            'reviewer',
            $this->userId
        );

        $service->publish($this->entityType, $this->entityId);
    }

    /**
     * Test that publisher role cannot publish due to deferred version resolution
     * 
     * In Phase 14, version resolution is deferred, so even publishers get blocked
     * by the intentional limitation, ensuring we don't have partial implementations
     * that could create security or data consistency issues.
     */
    public function testPublisherCannotPublishDueToDeferredVersionResolution()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Version resolution is not implemented');

        $service = new AuthoringService(
            $this->mockRepository,
            $this->tenantId,
            'publisher',
            $this->userId
        );

        $service->publish($this->entityType, $this->entityId);
    }

    /**
     * Test tenant context validation on service instantiation
     */
    public function testMissingTenantContextFails()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Tenant context');

        // Clear tenant context
        TenantContext::unset();

        new AuthoringService(
            $this->mockRepository,
            'different-tenant',
            'publisher',
            $this->userId
        );
    }

    /**
     * Test tenant ID mismatch fails
     */
    public function testTenantIdMismatchFails()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Tenant context');

        new AuthoringService(
            $this->mockRepository,
            'different-tenant',
            'publisher',
            $this->userId
        );
    }

    /**
     * Test that version resolution throws exception as expected
     */
    public function testVersionResolutionThrowsException()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Version resolution is not implemented');

        $service = new AuthoringService(
            $this->mockRepository,
            $this->tenantId,
            'publisher',
            $this->userId
        );

        // Any method that calls getVersionId() will throw
        $service->publish($this->entityType, $this->entityId);
    }

    /**
     * Test that author can create draft
     */
    public function testAuthorCanCreateDraft()
    {
        $testData = ['title' => 'Test Draft'];
        $expectedResult = ['id' => 1, 'state' => 'draft', 'title' => 'Test Draft'];
        
        $this->mockRepository->expects($this->once())
            ->method('createDraft')
            ->with(
                $this->entityType,
                $this->entityId,
                $testData,
                $this->userId
            )
            ->willReturn($expectedResult);

        $service = new AuthoringService(
            $this->mockRepository,
            $this->tenantId,
            'author',
            $this->userId
        );

        $result = $service->createDraft($this->entityType, $this->entityId, $testData);
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Test that reviewer cannot submit for review due to deferred version resolution
     * 
     * While reviewers should be able to submit for review, this test verifies that
     * the operation fails with the expected exception until version resolution is implemented.
     */
    public function testReviewerCannotSubmitForReviewDueToDeferredVersionResolution()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Version resolution is not implemented');

        $service = new AuthoringService(
            $this->mockRepository,
            $this->tenantId,
            'reviewer',
            $this->userId
        );

        $service->submitForReview($this->entityType, $this->entityId);
    }

    /**
     * Test that unauthorized user cannot archive
     */
    public function testAuthorCannotArchive()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Insufficient permissions');

        $service = new AuthoringService(
            $this->mockRepository,
            $this->tenantId,
            'author',
            $this->userId
        );

        $service->archive($this->entityType, $this->entityId);
    }

    /**
     * Test that unauthorized user cannot retract
     */
    public function testAuthorCannotRetract()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Insufficient permissions');

        $service = new AuthoringService(
            $this->mockRepository,
            $this->tenantId,
            'author',
            $this->userId
        );

        $service->retract($this->entityType, $this->entityId);
    }
}
