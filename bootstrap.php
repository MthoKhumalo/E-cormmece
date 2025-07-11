<?php

// Define a constant to distinguish test runs
define('PHPUNIT_RUNNING', true);

// Include the Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Mock session_start() to avoid session conflicts during tests
if (!function_exists('session_start')) {
    function session_start() {
        
        // Simulate an empty session array for testing
        $_SESSION = [];
    }
}
