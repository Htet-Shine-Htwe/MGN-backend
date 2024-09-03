<?php

use Illuminate\Support\Facades\Route;

use Tests\Support\UserAuthenticated;

uses()->group('admin','api','admin-auth');
uses(UserAuthenticated::class);

test('admin login route exists', function () {
    // check if the route exists in Route
    $this->assertTrue(Route::has('api.admin.login'));

});

test('request body is required', function () {

    $this->json('POST', route('api.admin.login'))
        ->assertStatus(422)
        ->assertJson([
            'message' => 'The email field is required. (and 1 more error)',
            'errors' => [
                'email' => ['The email field is required.'],
                'password' => ['The password field is required.'],
            ]
        ]);
});

test("invalid credintials", function () {
    $response = $this->json('POST', route('api.admin.login'), [
        'email' => 'wrong@gmail.com',
        'password' => 'password',
    ]);

    $response->assertStatus(422)
        ->assertJson([
            'message' => 'These credentials do not match our records.'
        ]);
});

test("can login successfully",function(){
    $admin = \App\Models\Admin::factory()->create();
    $response = $this->json('POST', route('api.admin.login'), [
        'email' => $admin->email,
        'password' => 'password',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'token',
            'user'
        ]);
});


test('change password route exists', function () {
    // check if the route exists in Route
    $this->assertTrue(Route::has('api.admin.change-password'));
});



test("can't change password without auth",function(){
    $response = $this->postJson(route('api.admin.change-password'),[
        'current_password' => 'password',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(401)
        ->assertJson([
            'message' => 'Unauthenticated.'
        ]);
});

test("change password request body is required", function () {
    $this->setupAdmin();
    $response = $this->authenticatedAdmin($this->admin)->postJson(route('api.admin.change-password'),[]);


    $response->assertStatus(422)
        ->assertJsonStructure([
            'message' ,
            'errors'
        ]);
});

test("change password with invalid current password", function () {
    $this->setupAdmin();
    $response = $this->authenticatedAdmin($this->admin)->postJson(route('api.admin.change-password'),[
        'old_password' => 'wrongpassword',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);


    $response->assertStatus(422)
        ->assertJson([
            'message' => 'Old Password is Incorrect',
        ]);
});

test("change password successfully", function () {
    $this->setupAdmin();
    $response = $this->authenticatedAdmin($this->admin)->postJson(route('api.admin.change-password'),[
        'old_password' => 'password',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Password was updated successfully',
        ]);
});

test('admin can logout with token delete', function () {
    $this->setupAdmin();
    $response = $this->authenticatedAdmin($this->admin)->postJson(route('api.admin.logout'));

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Logged out successfully',
        ]);
});
