# CI4 Architecture

## Overview
Ai-cms uses CodeIgniter 4's MVC architecture with additions for AI and multi-tenancy.

## Components
- **Controllers**: Handle requests, integrate AI.
- **Models**: Data access, with AI processing.
- **Views**: Render content, including AI suggestions.
- **Libraries**: Custom AI and utility classes.
- **Filters**: Security and tenant middleware.

## Database
- Uses migrations for schema.
- Entities for data objects.

## AI Integration
- External API calls in services.
- Cached responses for performance.

## Multi-Tenancy
- Tenant-specific data isolation.
- Configurable per tenant.