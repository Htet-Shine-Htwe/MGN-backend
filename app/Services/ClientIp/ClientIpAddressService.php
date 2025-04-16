<?php

namespace App\Services\ClientIp;

use App\Models\LoginHistory;
use App\Models\User;
use hisorange\BrowserDetect\Facade as Browser;
use Illuminate\Support\Facades\Log;
use Stevebauman\Location\Facades\Location;
use Stevebauman\Location\Position;

class ClientIpAddressService
{
    public function getClientInfo(string $ip): bool|Position
    {
        Log::info('ClientIpAddressService R1', ['client_ip' => $ip]);
        return Location::get($ip);
    }

    public function saveRecord(User $user, string $ip): bool
    {
        if ($user == null) {
            return false;
        }

        $location = $this->getClientInfo($ip);

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
