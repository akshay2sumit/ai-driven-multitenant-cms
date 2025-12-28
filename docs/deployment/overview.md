# Deployment Guide

*Last Updated: 2025-12-27*  
*Governance Version: 1.3.3 LTS*  
*Phase: 9 - Public Runtime Boundary*

## Overview
This guide provides deployment instructions for the AI-Driven Multi-Tenant CMS, focusing on the Phase 9 public runtime boundary implementation.

## System Requirements

### Server Requirements
- PHP 8.1 or higher
- MySQL 8.0+ or MariaDB 10.5+
- Web server (Apache/Nginx)
- Composer 2.0+

### PHP Extensions
- PDO PHP Extension
- cURL
- JSON
- MBString
- XML
- OpenSSL

## Deployment Steps

### 1. Server Setup
```bash
# Clone the repository
git clone [repository-url] /var/www/aibos
cd /var/www/aibos

# Install dependencies
composer install --no-dev --optimize-autoloader

# Set up environment
cp env .env
# Edit .env with your configuration
```

### 2. Database Configuration
```bash
# Run migrations
php spark migrate

# Seed initial data (development only)
php spark db:seed DatabaseSeeder
```

### 3. Web Server Configuration

#### Apache (.htaccess)
Ensure your `.htaccess` file in the `public` directory contains:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    
    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule ^ - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
    
    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)/$ /$1 [L,R=301]
    
    # Handle Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

#### Nginx
```nginx
server {
    listen 80;
    server_name example.com;
    root /var/www/aibos/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
    }
}
```

## Phase 9: Public Runtime Boundary

### Public URL Structure
- Admin Interface: `/t/{tenant}/...`
- Public Site: `/p/{tenant}/...` (404-by-design)

### Security Considerations
- Public routes are read-only by design
- All public endpoints return 404 (fail-closed)
- No content is accessible through public routes
- Tenant isolation is strictly enforced

### Verifying the Installation
1. Access the admin interface: `https://yourdomain.com/t/your-tenant/admin`
2. Verify public routes return 404: `https://yourdomain.com/p/your-tenant/any-path`
   - Expected: 404 Not Found

## Troubleshooting

### Public Routes Return 404
This is expected behavior in Phase 9. The public runtime boundary is established but intentionally returns 404 for all requests.

### Database Connection Issues
- Verify database credentials in `.env`
- Ensure the database server is running
- Check user permissions

### File Permissions
```bash
# Set proper permissions
chown -R www-data:www-data /var/www/aibos
chmod -R 755 /var/www/aibos/writable
```

## Monitoring
- Check application logs: `tail -f /var/www/aibos/writable/logs/log-*.php`
- Monitor PHP-FPM/Apache/Nginx error logs

## Backups
Regularly back up:
- Database
- `writable/uploads/` directory
- `.env` file

## Upgrading
1. Pull the latest changes
2. Run database migrations: `php spark migrate`
3. Clear cache: `php spark cache:clear`
