<?php

use Database\Seeders\CategorySeeder;
use Database\Seeders\MogouSeeder;
use Database\Seeders\SubscriptionSeeder;
use Database\Seeders\UserSeeder;
use App\Models\User;
use App\Repo\User\Favorite\UserFavoriteRepo;

// Group the test
uses()->group('unit', 'user-favorite-repo');

beforeEach(function() {
    // Set configuration
    config(['control.test.mogous_count' => 20]);

    // Seed the database
    $this->seed([
        SubscriptionSeeder::class,
        UserSeeder::class,
        CategorySeeder::class,
        MogouSeeder::class,
    ]);

    // Create a user instance
    $this->individual_user = User::factory()->create();
});

test('set method successfully sets user', function () {
    $userFavoriteRepo = new UserFavoriteRepo(null);

    $userFavoriteRepo->setUser($this->individual_user);

    expect($userFavoriteRepo->user)->toBe($this->individual_user);
});


test('addFavorite method successfully adds favorite', function () {
    $userFavoriteRepo = new UserFavoriteRepo(null);

    $userFavoriteRepo->setUser($this->individual_user);

    $userFavoriteRepo->addFavorite(1);

    expect($userFavoriteRepo->user->favorites->count())->toBe(1);
});

test("duplicate favorite doesn't add to favorites", function () {
    $userFavoriteRepo = new UserFavoriteRepo(null);

    $userFavoriteRepo->setUser($this->individual_user);

    $userFavoriteRepo->addFavorite(1);
    $userFavoriteRepo->addFavorite(1);

    expect($userFavoriteRepo->user->favorites->count())->toBe(1);
});

test('removeFavorite method successfully removes favorite', function () {
    $userFavoriteRepo = new UserFavoriteRepo(null);

    $userFavoriteRepo->setUser($this->individual_user);

    $userFavoriteRepo->addFavorite(1);

    $userFavoriteRepo->removeFavorite(1);

    expect($userFavoriteRepo->user->favorites->count())->toBe(0);
});

test('getFavorites method successfully gets favorites', function () {
    $userFavoriteRepo = new UserFavoriteRepo(null);

    $userFavoriteRepo->setUser($this->individual_user);

    $userFavoriteRepo->addFavorite(1);
    $userFavoriteRepo->addFavorite(2);

    expect($userFavoriteRepo->getFavorites())->toBe([1, 2]);
});
