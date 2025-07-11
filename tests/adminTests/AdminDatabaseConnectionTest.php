<?php
use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../../database/DBConn.inc.php');

class AdminDatabaseConnectionTest extends TestCase
{
    public function testAdminDatabaseConnection()
    {
        $db = DatabaseConnection::getInstance()->getConnection();
        $this->assertNotNull($db, "Database connection should not be null.");
        $this->assertInstanceOf(PDO::class, $db, "Database connection should be an instance of PDO.");
    }
}
