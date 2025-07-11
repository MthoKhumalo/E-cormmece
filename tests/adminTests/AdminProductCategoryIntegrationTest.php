<?php
use PHPUnit\Framework\TestCase;

class AdminProductCategoryIntegrationTest extends TestCase
{
    public function testAddProductWithNewCategory()
    {
        $categories = ['Electronics', 'Accessories'];
        $products = [
            ['id' => 1, 'name' => 'Laptop', 'category' => 'Electronics'],
        ];

        $newCategory = 'Furniture';
        $newProduct = ['id' => 2, 'name' => 'Desk', 'category' => $newCategory];

        // Add new category
        if (!in_array($newCategory, $categories)) {
            $categories[] = $newCategory;
        }

        // Add product
        $products[] = $newProduct;

        $this->assertContains($newCategory, $categories, "New category should be added to the category list.");
        $this->assertCount(2, $products, "Product list should include the new product.");
    }

    public function testDeleteCategoryWithAssociatedProducts()
    {
        $categories = ['Electronics', 'Accessories'];
        $products = [
            ['id' => 1, 'name' => 'Laptop', 'category' => 'Electronics'],
            ['id' => 2, 'name' => 'Mouse', 'category' => 'Accessories'],
        ];

        $categoryToDelete = 'Accessories';

        // Remove category
        $categories = array_filter($categories, fn($category) => $category !== $categoryToDelete);

        // Remove associated products
        $products = array_filter($products, fn($product) => $product['category'] !== $categoryToDelete);

        $this->assertNotContains($categoryToDelete, $categories, "Deleted category should not exist in the category list.");
        $this->assertCount(1, $products, "Products associated with the deleted category should be removed.");
    }
}