<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait FakeIpHeader
{
    public function setupHeader()
    {
        $request = Request::createFromGlobals();

        // Mock IP Address
        $request->setTrustedProxies([], Request::HEADER_X_FORWARDED_FOR);
        $request->server->set('REMOTE_ADDR', $this->client_ip);

        return $request;
    }
}
