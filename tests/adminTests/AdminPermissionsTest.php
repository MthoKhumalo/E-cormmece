<?php
use PHPUnit\Framework\TestCase;

class AdminPermissionsTest extends TestCase
{
    public function testAdminAccessWithoutPermission()
    {
        $userRole = 'editor'; // A role with limited access
        $adminActions = ['delete_product', 'edit_user', 'view_reports'];

        foreach ($adminActions as $action) {
            $hasAccess = $this->hasPermission($userRole, $action);
            $this->assertFalse($hasAccess, "Role {$userRole} should not have permission for {$action}.");
        }
    }

    public function testAdminAccessWithPermission()
    {
        $userRole = 'Owner'; // Full-access role
        $adminActions = ['delete_product', 'edit_user', 'view_reports'];

        foreach ($adminActions as $action) {
            $hasAccess = $this->hasPermission($userRole, $action);
            $this->assertTrue($hasAccess, "Role {$userRole} should have permission for {$action}.");
        }
    }

    private function hasPermission($role, $action)
    {
        $permissions = [
            'Owner' => ['delete_product', 'edit_user', 'view_reports'],
            'editor' => ['edit_content', 'view_content'],
        ];

        return in_array($action, $permissions[$role] ?? []);
    }
}