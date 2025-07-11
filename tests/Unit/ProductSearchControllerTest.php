<?php

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../../database/DBConn.inc.php');
require_once (__DIR__ . '/../../database/search/search_model.inc.php');
require_once (__DIR__ . '/../../database/search/search_contr.inc.php');

class ProductSearchControllerTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        // Get the database connection
        $this->pdo = DatabaseConnection::getInstance()->getConnection();
    }

    public function testProductNotFoundReturnsTrueForInvalidSearchTerm()
    {
        $productSearch = "non_existing_category";

        $result = product_not_found($this->pdo, $productSearch);

        // Assert that the function returns true for an invalid search term
        $this->assertTrue($result, "product_not_found did not return true for an invalid search term.");
    }

    public function testProductNotFoundReturnsFalseForValidSearchTerm()
    {
        $productSearch = "Laptop"; // Use a valid category name from your database.

        $result = product_not_found($this->pdo, $productSearch);

        // Assert that the function returns false for a valid search term
        $this->assertFalse($result, "product_not_found did not return false for a valid search term.");
    }
}
