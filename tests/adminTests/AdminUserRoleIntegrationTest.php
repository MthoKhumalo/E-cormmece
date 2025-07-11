<?php
use PHPUnit\Framework\TestCase;

class AdminUserRoleIntegrationTest extends TestCase
{
    public function testAssignRoleToUser()
    {
        $users = [
            ['id' => 1, 'name' => 'Alice', 'role' => 'user'],
            ['id' => 2, 'name' => 'Bob', 'role' => 'user'],
        ];

        $userId = 2;
        $newRole = 'admin';

        foreach ($users as &$user) {
            if ($user['id'] === $userId) {
                $user['role'] = $newRole;
            }
        }

        $this->assertEquals('admin', $users[1]['role'], "The user's role should be updated to admin.");
    }

    public function testRoleChangeAffectsPermissions()
    {
        $roles = [
            'user' => ['view_content'],
            'admin' => ['view_content', 'edit_content', 'delete_content'],
        ];

        $userRole = 'admin';
        $permissions = $roles[$userRole] ?? [];

        $this->assertContains('delete_content', $permissions, "Admin role should include delete permissions.");
        $this->assertCount(3, $permissions, "Admin role should have exactly three permissions.");
    }
}