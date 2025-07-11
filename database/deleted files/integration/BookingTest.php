<?php

use PHPUnit\Framework\TestCase;

// Start the session for testing (if not already started)
if (session_status() === PHP_SESSION_NONE) {
    ob_start();  // Start output buffering to prevent headers being sent
    session_start();
}
class BookingTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        
        // Mock database connection
        $this->pdo = $this->createMock(PDO::class);
    }

    public function testBookingFormSubmission()
    {
        // Set session variables for the test
        $_SESSION['user_id'] = 27;
        $_SESSION['user_name'] = 'Mthokozisi'; // Adding the missing session key

        $_SERVER["REQUEST_METHOD"] = "POST";  // Simulating POST request

        // Prepare test form data
        $_POST = [
            'user_id' => 27, // Ensure this key is set for the test
            'duration' => '20min',
            'booking_time' => '8:00-10:00',
            'booked_date' => '2024-11-10'
        ];

        // Mock database statement
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())->method('execute')->willReturn(true);

        // Mock PDO prepare method
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        // Execute the booking.php logic
        ob_start();  // Capture output
        include 'booking.php';  // This will run the actual logic of booking.php
        $output = ob_get_clean();  // Get captured output

        // Assert that the booking was successfully submitted
        $this->assertStringContainsString('Booking successfully submitted!', $output);
    }
}
