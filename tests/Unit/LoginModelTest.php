<?php

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../../database/DBConn.inc.php');
require_once (__DIR__ . '/../../database/logins/login_model.inc.php');

class LoginModelTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = DatabaseConnection::getInstance()->getConnection();
    }

    public function testGetUserWithValidEmailAndAdminTable()
    {
        $email = "Gift@techframe.com"; // Replace with a valid email in the 'admins' table
        $result = get_user($this->pdo, $email, 'admins');

        $this->assertNotEmpty($result, "User should be found in the 'admins' table.");
        $this->assertSame($email, $result['email'], "Retrieved email does not match the expected email.");
    }

    public function testGetUserWithInvalidEmail()
    {
        $email = "nonexistent@example.com";
        $result = get_user($this->pdo, $email, 'admins');

        $this->assertFalse($result, "No user should be found for an invalid email.");
    }

    public function testGetUserWithValidEmailAndCustomersTable()
    {
        $email = "zake@techframe.co.za"; // Replace with a valid email in the 'customers' table
        $result = get_user($this->pdo, $email, 'customers');

        $this->assertNotEmpty($result, "User should be found in the 'customers' table.");
        $this->assertSame($email, $result['email'], "Retrieved email does not match the expected email.");
    }
}
