<?php

namespace App\Controllers\Public;

use App\Controllers\BaseController;
use App\Services\PublishingRuntime;
use CodeIgniter\HTTP\ResponseInterface;

class Home extends BaseController
{
    protected $publishingRuntime;
    
    public function __construct()
    {
        $this->publishingRuntime = new PublishingRuntime();
    }
    
    /**
     * Public entry point for tenant content
     * 
     * @param string $tenant Tenant identifier
     * @return ResponseInterface
     */
    public function index(string $tenant): ResponseInterface
    {
        // Log minimal request data
        log_message('info', sprintf(
            'Public request - Tenant: %s, Path: %s',
            $tenant,
            $this->request->getUri()->getPath()
        ));

        // Enforce read-only at the HTTP method level
        if (!in_array($this->request->getMethod(), ['GET', 'HEAD'])) {
            return $this->response->setStatusCode(405, 'Method Not Allowed');
        }

        // Get the content path (everything after the tenant segment)
        $path = trim(str_replace("/p/{$tenant}", '', $this->request->getUri()->getPath()), '/');
        
        // In a real implementation, you would fetch the content from your data store
        // For this minimal implementation, we'll use a placeholder
        $content = $this->getContent($tenant, $path);
        
        // Check if content exists and is publishable
        if (!$content) {
            return $this->response->setStatusCode(404, 'Not Found');
        }
        
        // Check publishability using the PublishingRuntime
        $publishable = $this->publishingRuntime->evaluatePublishability([
            'tenant_id' => $tenant,
            'current_state' => $content['current_state'] ?? 'draft',
            'publish_at' => $content['publish_at'] ?? null,
            'expire_at' => $content['expire_at'] ?? null
        ]);
        
        // Log the publishability decision
        log_message('info', sprintf(
            'Publishability check - Tenant: %s, Publishable: %s, Reason: %s',
            $tenant,
            $publishable['is_publishable'] ? 'yes' : 'no',
            $publishable['reason']
        ));
        
        // Return 404 if not publishable
        if (!$publishable['is_publishable']) {
            return $this->response->setStatusCode(404, 'Not Found');
        }
        
        // Return the minimal HTML response
        return $this->response
            ->setContentType('text/html')
            ->setBody($this->renderMinimalHtml($content));
    }
    
    /**
     * Get content from the data store
     * 
     * This is a placeholder implementation. In a real application, this would
     * fetch content from a database or other data store.
     * 
     * @param string $tenant
     * @param string $path
     * @return array|null
     */
    protected function getContent(string $tenant, string $path): ?array
    {
        // In a real implementation, you would fetch the content from your data store
        // based on the tenant and path. For this example, we'll return a placeholder.
        
        // Default to 'index' if path is empty
        if (empty($path)) {
            $path = 'index';
        }
        
        // This is a simplified example - in reality, you'd query your database here
        $content = [
            'id' => 'example-content',
            'tenant_id' => $tenant,
            'path' => $path,
            'title' => 'Example Content',
            'body' => '<h1>Example Content</h1><p>This is a minimal example of published content.</p>',
            'current_state' => 'published',
            'created_at' => date('c'),
            'updated_at' => date('c')
        ];
        
        return $content;
    }
    
    /**
     * Render minimal HTML output
     * 
     * @param array $content
     * @return string
     */
    protected function renderMinimalHtml(array $content): string
    {
        // This is intentionally minimal - no CSS, JS, or other assets
        return sprintf(
            '<!DOCTYPE html>\n' .
            '<html>\n' .
            '<head>\n' .
            '<meta charset="UTF-8">\n' .
            '<title>%s</title>\n' .
            '</head>\n' .
            '<body>\n' .
            '%s\n' .
            '</body>\n' .
            '</html>',
            htmlspecialchars($content['title'] ?? 'Untitled'),
            $content['body'] ?? ''
        );
    }
}
