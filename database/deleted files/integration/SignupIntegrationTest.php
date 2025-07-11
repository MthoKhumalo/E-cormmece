<?php

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../database/DBConn.inc.php');
require_once (__DIR__ . '/../database/registration/signup_model.inc.php');
require_once (__DIR__ . '/../database/registration/signup_contr.inc.php');

class SignupIntegrationTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = DatabaseConnection::getInstance()->getConnection();
    }

    public function testSignupSuccess()
    {

        // Simulate a POST request
        $_SERVER["REQUEST_METHOD"] = "POST";

        $_POST = [
            "firstname" => "John",
            "lastname" => "Doe",
            "email" => "integration_test@example.com",
            "phone" => "1234567890",
            "address" => "123 Test Street",
            "password" => "Password123"
        ];

        ob_start();
        include __DIR__ . '/../database/registration/signup.inc.php'; // Include the logic file
        ob_end_clean();

        // Assert customer was added
        $result = get_username_email($this->pdo, $_POST["email"]);
        $this->assertNotEmpty($result, "Customer should be added during the signup process.");

        // Assert redirection to login
        $this->assertStringContainsString(
            "Location: ../../login.php",
            xdebug_get_headers(),
            "Signup should redirect to login."
        );
    }

    public function testSignupFailureDueToExistingEmail()
    {
        // Simulate a POST request
        $_SERVER["REQUEST_METHOD"] = "POST";

        $_POST = [
            "firstname" => "John",
            "lastname" => "Doe",
            "email" => "zake@techframe.co.za", // Use an existing email
            "phone" => "1234567890",
            "address" => "123 Test Street",
            "password" => "Password123"
        ];

        ob_start();
        include __DIR__ . '/../database/registration/signup.inc.php'; // Include the logic file
        $output = ob_get_clean();

        // Assert error session
        $this->assertArrayHasKey("email_taken", $_SESSION["errors_signup"], "An error should be set for existing email.");

        // Assert redirection to registration
        $this->assertStringContainsString("Location: ../../registration.php", xdebug_get_headers(), "Signup should redirect to registration on error.");
    }
}
