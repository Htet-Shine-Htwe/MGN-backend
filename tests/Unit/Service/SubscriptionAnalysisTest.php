<?php

use App\Models\Subscription;
use App\Models\UserSubscription;
use App\Services\Subscription\SubscriptionAnalysis;
use Database\Seeders\SubscriptionSeeder;
use Database\Seeders\UserSeeder;
use Carbon\Carbon;

uses()->group('service', 'subscription-analysis');

beforeEach(function () {
    config(['control.test.users_count' => 40]);
    $this->seed([
        SubscriptionSeeder::class,
        UserSeeder::class
    ]);
    $this->subscriptionAnalysis = new SubscriptionAnalysis();

    $this->this_month_test_day = Carbon::now()->startOfMonth()->addDays(14);
    $this->prev_month_test_day = Carbon::now()->subMonthNoOverflow()->startOfMonth()->addDays(14);

    // Store the counts as class properties
    $this->this_month_count_1 = 5;
    $this->this_month_count_2 = 20;
    $this->prev_month_count_1 = 10;
    $this->prev_month_count_2 = 5;

    // Use these properties to create subscriptions
    UserSubscription::factory()->count($this->this_month_count_1)->create([
        'subscription_id' => 1,
        'created_at' => $this->this_month_test_day
    ]);

    UserSubscription::factory()->count($this->this_month_count_2)->create([
        'subscription_id' => 2,
        'created_at' => $this->this_month_test_day
    ]);

    UserSubscription::factory()->count($this->prev_month_count_1)->create([
        'subscription_id' => 1,
        'created_at' => $this->prev_month_test_day
    ]);

    UserSubscription::factory()->count($this->prev_month_count_2)->create([
        'subscription_id' => 2,
        'created_at' => $this->prev_month_test_day
    ]);
});

it("can get the previous month subscriptions", function () {
    $this->assertEquals($this->prev_month_count_1 + $this->prev_month_count_2, $this->subscriptionAnalysis->getPreviousMonthSubscriptions());
});

it("can get the current month subscriptions", function () {
    $this->assertEquals($this->this_month_count_1 + $this->this_month_count_2, $this->subscriptionAnalysis->getCurrentMonthSubscriptions());
});

it("can get the previous month profit", function () {
    $this->assertEquals(Subscription::find(1)->price * $this->prev_month_count_1 + Subscription::find(2)->price * $this->prev_month_count_2, $this->subscriptionAnalysis->getPreviousMonthProfit());
});

it("can get the current month profit", function () {
    $this->assertEquals(Subscription::find(1)->price * $this->this_month_count_1 + Subscription::find(2)->price * $this->this_month_count_2, $this->subscriptionAnalysis->getCurrentMonthProfit());
});

