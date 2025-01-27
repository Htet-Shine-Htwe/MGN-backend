<?php

namespace Tests\Support;
use Illuminate\Support\Facades\Storage;

trait TestStorage
{

    protected function bootStorage()
    {
        Storage::fake('testStorage');

        config(['hydrastorage.provider' => 'testStorage']);
    }

    protected function assertInStorage(string $path)
    {
        $this->assertTrue(Storage::disk('testStorage')->exists('public/'.$path));
    }

}
