<?php

use App\Models\Mogou;
use App\Repo\Admin\SubMogouRepo\SubMogouActionRepo;
use Database\Seeders\CategorySeeder;
use Tests\Support\TestStorage;
use Tests\Support\UserAuthenticated;
use Illuminate\Http\UploadedFile;

uses()->group('admin','api','admin-mogou','admin-submogou-action');
uses(UserAuthenticated::class);
uses(TestStorage::class);

beforeEach(function(){
    $this->seed([
        CategorySeeder::class
    ]);
    $this->setupAdmin();

    $this->bootStorage();

    $this->mogou = Mogou::factory()->create();

});

dataset('sub-mogou-data-collection',[
    fn() => [
        'title' => 'Sub Mogou Title',
        'chapter_number' => 1,
        'mogou_id' => $this->mogou->id
    ],
    fn() => [
        'title' => 'Sub Mogou Title 2',
        'chapter_number' => 1,
        'mogou_id' => $this->mogou->id
    ],
]);


test("create new draft sub mogou with mogou id",function($data)
{
    $response = $this->postJson(route('api.admin.sub-mogous.saveNewDraft'),[
        'title' => $data['title'],
        'chapter_number' => $data['chapter_number'],
        'mogou_id' => $data['mogou_id']
    ]);

    $response->assertStatus(201);
    $this->assertDatabaseHas($this->mogou->rotation_key . '_sub_mogous',[
        'title' => $data['title'],
    ]);
    // dd(DB::table($this->mogou->rotation_key . '_sub_mogous')->get());

})
->with('sub-mogou-data-collection');


test("validation sub mogou without mogou id",function($data)
{
    $response = $this->postJson(route('api.admin.sub-mogous.saveNewDraft'),[
        'title' => $data['title'],
        'chapter_number' => $data['chapter_number'],
    ]);
    $response->assertStatus(422);
    $response->assertJsonValidationErrors('mogou_id');
})
->with('sub-mogou-data-collection');

test("validation without cover in updating sub mogous cover",function($sub_mogou){

    $response = $this->postJson(route('api.admin.sub-mogous.saveNewDraft'),[
        'title' => $sub_mogou['title'],
        'chapter_number' => $sub_mogou['chapter_number'],
        'mogou_id' => $sub_mogou['mogou_id']
    ]);

    $subMogou = $response->json('sub_mogou');

    $response = $this->postJson(route('api.admin.sub-mogous.updateCover'),[
        'sub_mogou_id' => $subMogou['id'],
        'cover' => null
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('cover');
})
->with('sub-mogou-data-collection');

test("can successfully update the cover of sub mogous",function($sub_mogou){

    $response = $this->postJson(route('api.admin.sub-mogous.saveNewDraft'),[
        'title' => $sub_mogou['title'],
        'chapter_number' => $sub_mogou['chapter_number'],
        'mogou_id' => $sub_mogou['mogou_id']
    ]);

    $subMogou = $response->json('sub_mogou');

    $response = $this->postJson(route('api.admin.sub-mogous.updateCover'),[
        'mogou_id' => $subMogou['mogou_id'],
        'id' => $subMogou['id'],
        'slug' => $subMogou['slug'],
        'cover' => UploadedFile::fake()->image('cover.jpg')
    ]);

    $folder = (new SubMogouActionRepo())->generateSubMogouFolder($subMogou);

    $response->assertStatus(200);

    $this->assertDatabaseHas($this->mogou->rotation_key . '_sub_mogous',[
        'slug' => $subMogou['slug'],
    ]);
    $full_path = $folder . '/' . $response->json('sub_mogou.cover');

    $this->assertInStorage($full_path);
})
->group('current')
->with('sub-mogou-data-collection');
