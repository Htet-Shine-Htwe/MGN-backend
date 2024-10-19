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
                'title' => 'System Error',
                'description' => 'There is an error in the system causing unexpected behaviors.',
                'current_url' => 'https://example.com/error',
                'status' => 1,
                'image' => 'https://example.com/images/error1.jpg',
            ],
            [
                'title' => 'Login Issue',
                'description' => 'Users are unable to log in due to timeout issues.',
                'current_url' => 'https://example.com/login-issue',
                'status' => 0,
                'image' => null,
            ],
            [
                'title' => 'Missing Data',
                'description' => 'Some records are missing from the database after an update.',
                'current_url' => 'https://example.com/missing-data',
                'status' => 1,
                'image' => 'https://example.com/images/missing_data.jpg',
            ],
            [
                'title' => 'Payment Gateway Issue',
                'description' => 'The payment gateway is rejecting valid credit cards.',
                'current_url' => 'https://example.com/payment-gateway',
                'status' => 0,
                'image' => null,
            ],
            [
                'title' => 'High Latency',
                'description' => 'The application is experiencing slow responses during high traffic times.',
                'current_url' => 'https://example.com/latency',
                'status' => 1,
                'image' => 'https://example.com/images/latency.jpg',
            ],
            [
                'title' => 'Data Breach',
                'description' => 'Security vulnerability detected, resulting in a potential data breach.',
                'current_url' => 'https://example.com/data-breach',
                'status' => 1,
                'image' => null,
            ],
            [
                'title' => 'UI Glitch',
                'description' => 'Graphical glitch in the dashboard affecting user experience.',
                'current_url' => 'https://example.com/ui-glitch',
                'status' => 0,
                'image' => 'https://example.com/images/ui-glitch.jpg',
            ],
            [
                'title' => 'Email Notification Failure',
                'description' => 'Emails are not being sent to users after registration.',
                'current_url' => 'https://example.com/email-failure',
                'status' => 1,
                'image' => null,
            ],
            [
                'title' => 'API Timeout',
                'description' => 'Third-party API calls are timing out intermittently.',
                'current_url' => 'https://example.com/api-timeout',
                'status' => 0,
                'image' => 'https://example.com/images/api-timeout.jpg',
            ],
            [
                'title' => 'Data Sync Issue',
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
