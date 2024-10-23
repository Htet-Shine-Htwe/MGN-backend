<?php

namespace App\Services\ClientIp;

use App\Models\LoginHistory;
use App\Models\User;
use hisorange\BrowserDetect\Facade as Browser;
use Illuminate\Support\Facades\Request;
use Stevebauman\Location\Facades\Location;
use Stevebauman\Location\Position;

class ClientIpAddressService
{
    public function getClientInfo(): bool|Position
    {
        $client_ip = Request::getClientIp();

        return Location::get($client_ip);
    }

    public function saveRecord(): bool
    {
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        $location = $this->getClientInfo();

        $device = Browser::platformName() . " ( " . Browser::browserFamily() . " ) ";

        $country = $location instanceof Position ? $location->countryName : 'Unknown';
        $region = $location instanceof Position ? $location->regionName : 'Unknown';
        $locationString = "{$country}/{$region}";

        LoginHistory::create([
            'user_id' => $user->id,
            'location' => $locationString,
            'country' => $country,
            'device' => $device,
            'login_at' => now()
        ]);

        $user->last_login_at = now();
        $user->save();

        return true;
    }
}
