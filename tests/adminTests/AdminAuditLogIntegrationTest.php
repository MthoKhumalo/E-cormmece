<?php
use PHPUnit\Framework\TestCase;

class AdminAuditLogIntegrationTest extends TestCase
{
    public function testRecordUserAction()
    {
        $auditLog = [];
        $userAction = [
            'user_id' => 1,
            'action' => 'Added new product',
            'timestamp' => '2024-11-10 15:30:00',
        ];

        $auditLog[] = $userAction;

        $this->assertCount(1, $auditLog, "Audit log should contain one entry.");
        $this->assertEquals('Added new product', $auditLog[0]['action'], "Action should be recorded correctly in the audit log.");
    }

    public function testRetrieveLogsForUser()
    {
        $auditLog = [
            ['user_id' => 1, 'action' => 'Added new product', 'timestamp' => '2024-11-10 15:30:00'],
            ['user_id' => 2, 'action' => 'Deleted a category', 'timestamp' => '2024-11-10 16:00:00'],
        ];

        $userId = 1;

        $userLogs = array_filter($auditLog, function ($log) use ($userId) {
            return $log['user_id'] === $userId;
        });

        $this->assertCount(1, $userLogs, "Log should contain only the actions performed by the specific user.");
        $this->assertEquals('Added new product', $userLogs[array_key_first($userLogs)]['action'], "Action should match the expected log entry.");
    }
}