<?php

use App\Models\Admin;
use Database\Seeders\AdminPermissionSeeder;
use Illuminate\Support\Facades\Route;

use Tests\Support\UserAuthenticated;

uses()->group('admin','api','admin-roles');
uses(UserAuthenticated::class);


test("can get role route exists",function(){
    $this->assertTrue(Route::has('api.admin.roles.index'));
});

test('can get roles',function(){
    $this->setupAdmin();

    $response = $this->authenticatedAdmin()->getJson(route('api.admin.roles.index'))
        ->assertOk();

    $response->assertStatus(200);

    $response->assertJsonStructure([
        'roles' =>[
            '0'
        ]
        ]);
});

test("can create role",function(){
    $this->setupAdmin();

    $response = $this->authenticatedAdmin()->postJson(route('api.admin.roles.store'),[
        'name' => 'test_role',
        'permissions' => ['dashboard','admins','users','book-management','book-genre']
    ]);

    $response->assertStatus(201);

    $response->assertJson([
        'role' => [
            'name' => 'test_role',
            'guard_name' =>  'admin'
        ]
    ]);

    $this->assertDatabaseHas('roles',[
        'name' => 'test_role',
        'guard_name' => 'admin'
    ]);

});



test('can get permissions',function(){
    $this->setupAdmin();

    $response = $this->authenticatedAdmin()->getJson(route('api.admin.permissions.index'))
        ->assertOk();

    $status = $response->assertStatus(200);

    $response->assertJsonStructure([
        'permissions' =>[
            '0'
        ]
        ]);

});

test("get members with roles and pagination",function(int $count){
    $this->createOrgAdmin($count);

    $response = $this->setupAdmin()->getJson(route('api.admin.members.index'))
        ->assertOk();

    $total_count = Admin::count();
    expect(count($response['members']))->toBe($total_count);

    $role_collection = collect($response['members'])->pluck('roles.0.name')->toArray();

    expect($role_collection)->toBeArray();

    foreach($role_collection as $role){
        expect($role)->toBeIn(['moderator','admin']);
    }
})->with([
    10,
    20
]);
