<?php

namespace App\Services;

use InvalidArgumentException;
use RuntimeException;

class IpAddressService
{
    /**
     * Convert a human-readable IP address (IPv4 or IPv6) to its packed binary representation.
     *
     * @param string $ip
     * @return string
     * @throws InvalidArgumentException
     */
    public function pack(string $ip): string
    {
        $packed = bin2hex(inet_pton($ip));
        if ($packed === false) {
            throw new InvalidArgumentException("Invalid IP address: {$ip}");
        }
        return $packed;
    }

    /**
     * Convert a packed binary IP address back to its human-readable representation.
     *
     * @param string $packed
     * @return string
     * @throws RuntimeException
     */
    public function unpack(string $packed): string
    {
        $ip = inet_ntop(hex2bin($packed));
        if ($ip === false) {
            throw new RuntimeException("Invalid binary IP address.");
        }
        return $ip;
    }
}
