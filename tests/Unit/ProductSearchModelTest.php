<?php

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../../database/DBConn.inc.php');
require_once (__DIR__ . '/../../database/search/search_model.inc.php');

class ProductSearchModelTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        // Get the database connection
        $this->pdo = DatabaseConnection::getInstance()->getConnection();
    }

    public function testGetProductsWithValidSearchTerm()
    {
        $productSearch = "Laptop"; // Use a valid category name from your database for testing.

        $products = get_products($this->pdo, $productSearch);

        // Assert that products are fetched successfully
        $this->assertNotEmpty($products, "No products found for the given search term.");
        $this->assertContains($productSearch, array_column($products, 'category'), "Search term not found in product categories.");
    }

    public function testGetProductsWithInvalidSearchTerm()
    {
        $productSearch = "non_existing_category";

        $products = get_products($this->pdo, $productSearch);

        // Assert that no products are returned for an invalid category
        $this->assertEmpty($products, "Products found for an invalid search term.");
    }
}
