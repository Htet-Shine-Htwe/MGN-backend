<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $dummyReports = [
            [
                'title' => 'System Error 2',
                'description' => 'There is an error in the system causing unexpected behaviors.',
                'current_url' => 'https://example.com/error',
                'status' => 1,
                'image' => 'https://example.com/images/error1.jpg',
            ],
            [
                'title' => 'Login Issue 2',
                'description' => 'Users are unable to log in due to timeout issues.',
                'current_url' => 'https://example.com/login-issue',
                'status' => 0,
                'image' => null,
            ],
            [
                'title' => 'Missing Data 2',
                'description' => 'Some records are missing from the database after an update.',
                'current_url' => 'https://example.com/missing-data',
                'status' => 1,
                'image' => 'https://example.com/images/missing_data.jpg',
            ],
            [
                'title' => 'Payment Gateway Issue 2',
                'description' => 'The payment gateway is rejecting valid credit cards.',
                'current_url' => 'https://example.com/payment-gateway',
                'status' => 0,
                'image' => null,
            ],
            [
                'title' => 'High Latency 2',
                'description' => 'The application is experiencing slow responses during high traffic times.',
                'current_url' => 'https://example.com/latency',
                'status' => 1,
                'image' => 'https://example.com/images/latency.jpg',
            ],
            [
                'title' => 'Data Breach 2',
                'description' => 'Security vulnerability detected, resulting in a potential data breach.',
                'current_url' => 'https://example.com/data-breach',
                'status' => 1,
                'image' => null,
            ],
            [
                'title' => 'UI Glitch 2',
                'description' => 'Graphical glitch in the dashboard affecting user experience.',
                'current_url' => 'https://example.com/ui-glitch',
                'status' => 0,
                'image' => 'https://example.com/images/ui-glitch.jpg',
            ],
            [
                'title' => 'Email Notification Failure 2',
                'description' => 'Emails are not being sent to users after registration.',
                'current_url' => 'https://example.com/email-failure',
                'status' => 1,
                'image' => null,
            ],
            [
                'title' => 'API Timeout 2',
                'description' => 'Third-party API calls are timing out intermittently.',
                'current_url' => 'https://example.com/api-timeout',
                'status' => 0,
                'image' => 'https://example.com/images/api-timeout.jpg',
            ],
            [
                'title' => 'Data Sync Issue 2',
                'description' => 'Data is not syncing correctly between services.',
                'current_url' => 'https://example.com/data-sync',
                'status' => 1,
                'image' => null,
            ],
        ];

        foreach ($dummyReports as $report) {
            \App\Models\Report::create($report);
        }
    }
}
