<?php
use PHPUnit\Framework\TestCase;

class AdminBulkOperationsTest extends TestCase
{
    public function testAdminBulkDeleteProducts()
    {
        $products = [
            ['id' => 1, 'name' => 'Laptop', 'deleted_at' => null],
            ['id' => 2, 'name' => 'Camera', 'deleted_at' => null],
        ];

        $productIdsToDelete = [1, 2];

        $updatedProducts = array_map(function ($product) use ($productIdsToDelete) {
            if (in_array($product['id'], $productIdsToDelete)) {
                $product['deleted_at'] = date('Y-m-d');
            }
            return $product;
        }, $products);

        foreach ($updatedProducts as $product) {
            $this->assertNotNull($product['deleted_at'], "Product ID {$product['id']} should be marked as deleted.");
        }
    }

    public function testAdminBulkRestoreProducts()
    {
        $products = [
            ['id' => 1, 'name' => 'Laptop', 'deleted_at' => '2024-11-01'],
            ['id' => 2, 'name' => 'Camera', 'deleted_at' => '2024-11-01'],
        ];

        $productIdsToRestore = [1, 2];

        $updatedProducts = array_map(function ($product) use ($productIdsToRestore) {
            if (in_array($product['id'], $productIdsToRestore)) {
                $product['deleted_at'] = null;
            }
            return $product;
        }, $products);

        foreach ($updatedProducts as $product) {
            $this->assertNull($product['deleted_at'], "Product ID {$product['id']} should be restored.");
        }
    }
}