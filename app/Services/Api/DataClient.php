<?php

namespace App\Services\Api;

class DataClient
{

    public static function getMangaData()
    {
        $client = app('MangaTestClient');

        $response = $client->get('manga/top/all');

        return json_decode($response->getBody()->getContents(), true);
    }

}
