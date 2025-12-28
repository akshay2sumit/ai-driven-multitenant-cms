<?php
/**
 * Temporary migration runner
 */

use CodeIgniter\Database\BaseConnection;
use Config\App;
use Config\Database;
use CodeIgniter\Database\MigrationRunner;

// Set the environment
define('ENVIRONMENT', 'development');
define('APPPATH', realpath(__DIR__ . '/app') . DIRECTORY_SEPARATOR);
define('SYSTEMPATH', realpath(__DIR__ . '/vendor/codeigniter4/framework/system') . DIRECTORY_SEPARATOR);
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
define('WRITEPATH', realpath(APPPATH . '../writable') . DIRECTORY_SEPARATOR);

// Load the autoloader
require_once SYSTEMPATH . 'bootstrap.php';

// Load the database config
$config = new \Config\Database();
$db = Database::connect();

// Get the latest migration
$migration = new \App\Database\Migrations\AddPublishingFieldsToPages($db);

// Run the migration
try {
    echo "Running migration: AddPublishingFieldsToPages\n";
    $migration->up();
    echo "Migration completed successfully\n";
} catch (Throwable $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
