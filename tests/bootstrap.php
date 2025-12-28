<?php

// Disable route loading during tests
define('ENVIRONMENT', 'testing');

// Load the main CodeIgniter bootstrap
require_once __DIR__ . '/../vendor/codeigniter4/framework/system/Test/bootstrap.php';

// Load the test helper
helper('test');

// Load any test-specific configurations
$config = config('App');
$config->baseURL = 'http://example.com/';
$config->indexPage = 'index.php';

// Initialize the framework
new CodeIgniter\CodeIgniter($config);
