<?php

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../../database/DBConn.inc.php');
require_once (__DIR__ . '/../../database/registration/signup_model.inc.php');
require_once (__DIR__ . '/../../database/registration/signup_contr.inc.php');

class SignupControllerTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = DatabaseConnection::getInstance()->getConnection();
    }

    public function testInputEmptyWithEmptyFields()
    {
        $result = input_empty("", "Doe", "test@example.com", "123 Test Street", "Password123");
        $this->assertTrue($result, "input_empty should return true for empty fields.");
    }

    public function testInputEmptyWithNoEmptyFields()
    {
        $result = input_empty("John", "Doe", "test@example.com", "123 Test Street", "Password123");
        $this->assertFalse($result, "input_empty should return false for no empty fields.");
    }

    public function testUsernameEmailTakenWithExistingEmail()
    {
        $email = "zake@techframe.co.za"; // Use an existing email in your database
        $result = username_email_taken($this->pdo, $email);
        $this->assertTrue($result, "username_email_taken should return true for an existing email.");
    }

    public function testUsernameEmailTakenWithNonExistingEmail()
    {
        $email = "nonexistent@example.com";
        $result = username_email_taken($this->pdo, $email);
        $this->assertFalse($result, "username_email_taken should return false for a nonexistent email.");
    }

    public function testCheckPasswordCharactersWithValidPassword()
    {
        $pwd = "Password123";
        $result = check_password_characters($pwd);
        $this->assertFalse($result, "check_password_characters should return false for a valid password.");
    }

    public function testCheckPasswordCharactersWithInvalidPassword()
    {
        $pwd = "pass";
        $result = check_password_characters($pwd);
        $this->assertTrue($result, "check_password_characters should return true for an invalid password.");
    }
}
