<?php

namespace Config;

use App\Contracts\ReadOnlyPublishingRepositoryInterface;
use App\Models\PublishableEntityModel;
use App\Repositories\PublishableEntityRepository;
use App\Services\PublishingRuntime;
use CodeIgniter\Config\BaseService;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
    /*
     * public static function example($getShared = true)
     * {
     *     if ($getShared) {
     *         return static::getSharedInstance('example');
     *     }
     *
     *     return new \CodeIgniter\Example();
     * }
     */

    public static function contentPublishing($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('contentPublishing');
        }

        return new \App\Services\ContentPublishingService();
    }
    
    /**
     * Get the PublishingRuntime service
     * 
     * This provides read-only access to published content with strict tenant isolation.
     * 
     * @param bool $getShared Whether to return a shared instance
     * @return PublishingRuntime
     */
    public static function publishingRuntime($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('publishingRuntime');
        }
        
        // Create the repository with its dependencies
        $model = new PublishableEntityModel();
        $repository = new PublishableEntityRepository($model);
        
        // Return new PublishingRuntime instance with the repository
        return new PublishingRuntime($repository);
    }
}
