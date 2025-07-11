<?php

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../database/DBConn.inc.php');
require_once (__DIR__ . '/../database/logins/login_model.inc.php');
require_once (__DIR__ . '/../database/logins/login_contr.inc.php');

class LoginIntegrationTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        require_once (__DIR__ . '/../Config/config.inc.php');

        $this->pdo = DatabaseConnection::getInstance()->getConnection();
    }

    public function testAdminLoginSuccess()
    {
        $_SERVER["REQUEST_METHOD"] = "POST";

        $_POST = [
            "email" => "Gift@techframe.com", // Replace with a valid admin email
            "password" => "hgdR&h5dd"    // Replace with the correct password
        ];

        ob_start();
        include __DIR__ . '/../database/logins/login.inc.php'; // Include the logic file        
        ob_end_clean();

        $this->assertArrayHasKey("user_id", $_SESSION, "Admin login should set a user_id in session.");
        $this->assertEquals("admin", $_SESSION["user_role"], "Admin login should set the user role to 'admin'.");
    }

    public function testCustomerLoginSuccess()
    {
        $_SERVER["REQUEST_METHOD"] = "POST";

        $_POST = [
            "email" => "zake@techframe.co.za", // Replace with a valid customer email
            "password" => "456987Aa"       // Replace with the correct password
        ];

        ob_start();
        include __DIR__ . '/../database/logins/login.inc.php'; // Include the logic file 
        ob_end_clean();

        $this->assertArrayHasKey("user_id", $_SESSION, "Customer login should set a user_id in session.");
        $this->assertEquals("customer", $_SESSION["user_role"], "Customer login should set the user role to 'customer'.");
    }

    public function testLoginFailureDueToWrongEmail()
    {
        $_POST = [
            "email" => "wrong@example.com",
            "password" => "Password123"
        ];

        ob_start();
        include __DIR__ . '/../database/logins/login.inc.php'; // Include the logic file 
        ob_end_clean();

        $this->assertArrayHasKey("errors_login", $_SESSION, "Login should set errors in session for incorrect email.");
        $this->assertArrayHasKey("wrong_email", $_SESSION["errors_login"], "Error for wrong email should be set.");
    }

    public function testLoginFailureDueToWrongPassword()
    {
        $_POST = [
            "email" => "Gift@techframe.com", // Replace with a valid email
            "password" => "WrongPassword"
        ];

        ob_start();
        include __DIR__ . '/../database/logins/login.inc.php'; // Include the logic file 
        ob_end_clean();

        $this->assertArrayHasKey("errors_login", $_SESSION, "Login should set errors in session for incorrect password.");
        $this->assertArrayHasKey("wrong_password", $_SESSION["errors_login"], "Error for wrong password should be set.");
    }
}
