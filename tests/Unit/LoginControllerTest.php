<?php

use PHPUnit\Framework\TestCase;

require_once (__DIR__ . '/../../database/logins/login_contr.inc.php');

class LoginControllerTest extends TestCase
{
    public function testIsInputEmptyWithEmptyFields()
    {
        $result = is_input_empty("", "password123");
        $this->assertTrue($result, "is_input_empty should return true for empty email.");
    }

    public function testIsInputEmptyWithNonEmptyFields()
    {
        $result = is_input_empty("email@example.com", "password123");
        $this->assertFalse($result, "is_input_empty should return false for non-empty fields.");
    }

    public function testIsUsernameWrongWithEmptyResults()
    {
        $result = is_username_wrong(false);
        $this->assertTrue($result, "is_username_wrong should return true for empty results.");
    }

    public function testIsUsernameWrongWithValidResults()
    {
        $user = ["email" => "user@example.com", "pwrd" => "hashedpassword"];
        $result = is_username_wrong($user);
        $this->assertFalse($result, "is_username_wrong should return false for valid results.");
    }

    public function testIsPasswordWrongWithValidPassword()
    {
        $hashedPassword = password_hash("password123", PASSWORD_BCRYPT);
        $result = is_password_wrong("password123", $hashedPassword);

        $this->assertFalse($result, "is_password_wrong should return false for a correct password.");
    }

    public function testIsPasswordWrongWithInvalidPassword()
    {
        $hashedPassword = password_hash("password123", PASSWORD_BCRYPT);
        $result = is_password_wrong("wrongpassword", $hashedPassword);

        $this->assertTrue($result, "is_password_wrong should return true for an incorrect password.");
    }
}
