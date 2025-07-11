<?php

use PHPUnit\Framework\TestCase;

class adminUserWorkflowTest extends TestCase
{
    use ApiRequestHelper;

    public function testUserSignupAndLogin()
    {
        // Step 1: Sign up a new user
        $signupResponse = $this->post('/auth/signup', [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'securePassword',
        ]);
        $this->assertEquals(201, $signupResponse['status']);
        $this->assertArrayHasKey('user_id', $signupResponse['data']);

        // Step 2: Log in with credentials
        $loginResponse = $this->post('/auth/login', [
            'email' => 'john.doe@example.com',
            'password' => 'securePassword',
        ]);
        $this->assertEquals(200, $loginResponse['status']);
        $this->assertArrayHasKey('token', $loginResponse['data']);

        // Step 3: Fetch user profile
        $token = $loginResponse['data']['token'];
        $profileResponse = $this->get('/user/profile', $token);
        $this->assertEquals(200, $profileResponse['status']);
        $this->assertEquals('John Doe', $profileResponse['data']['name']);
    }
}
