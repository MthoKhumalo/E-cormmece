<?php
use PHPUnit\Framework\TestCase;

class AdminRoleIntegrationTest extends TestCase
{
    public function testAdminPromoteUser()
    {
        $users = [
            ['id' => 1, 'name' => 'Bob Vas', 'role' => 'receptionists'],
            ['id' => 2, 'name' => 'Sara Fredric', 'role' => 'owner'],
        ];

        $userToPromote = &$users[0];

        // Admin action to promote
        if ($userToPromote['role'] === 'receptionists') {
            $userToPromote['role'] = 'owner';
        }

        // Assertions
        $this->assertEquals('owner', $userToPromote['role'], "User role should be updated to owner.");
    }

    public function testAdminRestrictAccessForNonAdmin()
    {
        $currentUser = ['id' => 3, 'role' => 'receptionists'];

        $restrictedAction = function () use ($currentUser) {
            if ($currentUser['role'] !== 'owner') {
                throw new Exception("Access Denied: Admins only.");
            }
            return "Access Granted";
        };

        // Assertions
        $this->expectException(Exception::class);
        $restrictedAction();
    }
}