# Database Schema Design

## Overview
This document outlines the database schema for the AI-Driven Multi-Tenant CMS, following the single-database, shared-table approach defined in ADR-004.

## Global Tables

### `tenants`
- **Purpose**: Stores tenant information and configuration
- **Key Fields**: 
  - `id`: Unique tenant identifier
  - `name`: Display name of the tenant
  - `slug`: URL-friendly identifier
  - `status`: Active/inactive status
  - `created_at`: Timestamp of creation

### `users`
- **Purpose**: System users with cross-tenant access
- **Key Fields**:
  - `id`: Unique user identifier
  - `email`: User's email address
  - `password_hash`: Hashed password
  - `status`: Account status
  - `last_login`: Timestamp of last login

## Tenant-Scoped Tables

### `tenant_users`
- **Purpose**: Maps users to tenants and their roles
- **Key Fields**:
  - `tenant_id`: Reference to tenants table
  - `user_id`: Reference to users table
  - `role`: User's role within the tenant
  - `permissions`: JSON field for granular permissions

### `pages`
- **Purpose**: Stores page content and configuration
- **Key Fields**:
  - `id`: Unique page identifier
  - `tenant_id`: Owning tenant
  - `title`: Page title
  - `slug`: URL path segment
  - `content`: Page content (HTML/JSON)
  - `status`: Draft/published/archived
  - `published_at`: Publication timestamp

### `media`
- **Purpose**: Stores media assets
- **Key Fields**:
  - `id`: Unique media identifier
  - `tenant_id`: Owning tenant
  - `filename`: Original filename
  - `path`: Storage path
  - `mime_type`: File type
  - `size`: File size in bytes
  - `alt_text`: Accessibility text

### `settings`
- **Purpose**: Tenant-specific configuration
- **Key Fields**:
  - `tenant_id`: Owning tenant
  - `key`: Setting name
  - `value`: Setting value (JSON)
  - `type`: Data type of value

## Relationships
- A `tenant` has many `pages`
- A `tenant` has many `media` items
- A `tenant` has many `tenant_users`
- A `user` can belong to multiple `tenants` through `tenant_users`
- Each `tenant_user` has one `role`

## Notes
- All tenant-scoped tables include `tenant_id` for isolation
- Soft deletes are implemented via `deleted_at` timestamp
- All tables include `created_at` and `updated_at` timestamps
- Indexes will be added for frequently queried fields
- Foreign keys will be used to maintain referential integrity
