<?php

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../../database/DBConn.inc.php');
require_once (__DIR__ . '/../../database/registration/signup_model.inc.php');
class SignupModelTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = DatabaseConnection::getInstance()->getConnection();
    }

    public function testGetUsernameEmailWithExistingEmail()
    {
        $email = "zake@techframe.co.za"; // Use an existing email in your database
        $result = get_username_email($this->pdo, $email);

        $this->assertNotEmpty($result, "Email should exist in the database.");
        $this->assertSame($email, $result[0]['email'], "Fetched email does not match the expected email.");
    }

    public function testGetUsernameEmailWithNonExistingEmail()
    {
        $email = "nonexistent@example.com";
        $result = get_username_email($this->pdo, $email);

        $this->assertEmpty($result, "No email should be returned for a nonexistent email.");
    }

    public function testSetCustomerSuccessfully()
    {
        $fName = "John";
        $lName = "Doe";
        $email = "test@example.com";
        $phone = 1234567890;
        $addr = "123 Test Street";
        $pwd = "Password123";

        set_customer($this->pdo, $fName, $lName, $email, $phone, $addr, $pwd);

        $result = get_username_email($this->pdo, $email);
        $this->assertNotEmpty($result, "Customer was not inserted successfully.");
        $this->assertSame($email, $result[0]['email'], "Inserted email does not match.");
    }
}
