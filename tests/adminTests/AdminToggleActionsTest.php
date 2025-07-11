<?php
use PHPUnit\Framework\TestCase;

class AdminToggleActionsTest extends TestCase
{
    public function testAdminToggleRestoreAndRemove()
    {
        $product = ['id' => 14, 'deleted_at' => '2024-11-01'];
        $action = $product['deleted_at'] ? 'Restore' : 'Remove';

        $this->assertEquals('Restore', $action, "The action for deleted products should be 'Restore'.");

        $product['deleted_at'] = null;
        $action = $product['deleted_at'] ? 'Restore' : 'Remove';

        $this->assertEquals('Remove', $action, "The action for non-deleted products should be 'Remove'.");
    }
}
