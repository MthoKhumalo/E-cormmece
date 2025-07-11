<?php

// session_config.inc.php

try {
    if (php_sapi_name() !== 'cli' || !defined('PHPUNIT_RUNNING')) {
        ini_set('session.use_only_cookies', 1);
        ini_set('session.use_strict_mode', 1);

        session_set_cookie_params([
            'lifetime' => 1800,
            'domain' => 'localhost',
            'path' => '/',
            'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' || getenv('APP_ENV') === 'local',
            'httponly' => true,
            'samesite' => 'Strict',
        ]);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    if (isset($_SESSION["user_id"])) {
        if (!isset($_SESSION['last_regeneration']) || time() - $_SESSION['last_regeneration'] >= 3600) {
            regenerateSessionId(true);
        }
    } else {
        if (!isset($_SESSION['last_regeneration']) || time() - $_SESSION['last_regeneration'] >= 3600) {
            regenerateSessionId(false);
        }
    }
} catch (Exception $e) {
    if (defined('PHPUNIT_RUNNING') && PHPUNIT_RUNNING) {
        error_log("Redirect suppressed during PHPUnit tests: error.php");
    } else {
        header('Location: error.php');
        exit;
    }
}

// Function to regenerate session ID
function regenerateSessionId($isLoggedIn)
{
    try {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);  // Regenerate session ID

            if ($isLoggedIn) {
                $_SESSION['custom_session_id'] = session_id() . "_" . $_SESSION["user_id"];
            }

            $_SESSION["last_regeneration"] = time();
        } else {
            // Log this event for debugging purposes
            error_log("Attempted to regenerate session ID without an active session.");
            if (!defined('PHPUNIT_RUNNING')) {
                header('Location: error.php');
                exit;
            }
        }
    } catch (Exception $e) {
        error_log("Failed to regenerate session ID: " . $e->getMessage());
        if (!defined('PHPUNIT_RUNNING')) {
            header('Location: error.php');
            exit;
        }
    }
}