<?php

namespace App\Services\Partition;

class TablePartition
{

    protected static int $current_locked = 2;

    /** @var array<string> */
    protected array $rotationPrefix ;


    /**
     * Constructor with specific type for $rotationPrefix.
     *
     * @param array<string> $rotationPrefix
     */
    public function __construct(array $rotationPrefix)
    {
        $this->rotationPrefix = $rotationPrefix;
    }

    /**
     *  Get rotation keys.
     *
     * @return array<string>
     */
    public static function getRotationKeys(): array
    {
        return [
            "alpha","beta","gamma","delta","epsilon","zeta","eta","theta","iota","kappa","lambda","mu","nu","xi","omicron","pi","rho","sigma","tau","upsilon","phi","chi","psi","omega"
        ];
    }

     /**
     * Get available rotation keys based on current locked value.
     *
     * @return array<string>
     */
    public static function availableRotationKey(): array
    {
        return array_slice(self::getRotationKeys(), 0, self::$current_locked);
    }

     /**
     * Get a random rotation key from available keys.
     *
     * @return string
     */
    public static function getRandomRotationKey(): string
    {
        $keys = self::availableRotationKey();
        $random_key = array_rand($keys);
        return $keys[$random_key];
    }

     /**
     * Set the number of locked rotation keys.
     *
     * @param int $locked
     */
    public static function setLockedRotation(int $locked): void
    {
        self::$current_locked = $locked;
    }



}
