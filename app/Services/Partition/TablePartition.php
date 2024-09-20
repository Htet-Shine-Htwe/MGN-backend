<?php

namespace App\Services\Partition;

class TablePartition
{

    protected static int $current_locked = 3;

    protected array $rotationPrefix;

    public function __construct(array $rotationPrefix)
    {
        $this->rotationPrefix = $rotationPrefix;
    }

    public static function getRotationKeys()
    {
        return [
            "alpha","beta","gamma","delta","epsilon","zeta","eta","theta","iota","kappa","lambda","mu","nu","xi","omicron","pi","rho","sigma","tau","upsilon","phi","chi","psi","omega"
        ];
    }

    public static function availableRotationKey()
    {
        return array_slice(self::getRotationKeys(),0,self::$current_locked);
    }

    public static function getRandomRotationKey()
    {
        $keys = self::availableRotationKey();
        $random_key = array_rand($keys);
        return $keys[$random_key];
    }

    public static function setLockedRotation(int $locked)
    {
        self::$current_locked = $locked;
    }



}
