<?php

namespace App\Services\LexoRank;

class LexoRankGenerator
{

    public const MIN_CHAR = '0';
    public const MAX_CHAR = 'z';

    private $prev;
    private $next;

    /**
     * Rank constructor.
     */
    public function __construct(string $prev, string $next)
    {
        // If no previous value is provided, default to MIN_CHAR.
        $this->prev = $prev === '' ? self::MIN_CHAR : $prev;
        // If no next value is provided, default to MAX_CHAR.
        $this->next = $next === '' ? self::MAX_CHAR : $next;
    }

    /**
     * Instead of using a midpoint algorithm, we simply increment the previous rank.
     *
     * For example:
     *  - 'z' becomes 'za'
     *  - 'za' becomes 'zb'
     *  - 'zb' becomes 'zc'
     *
     * @return string
     */
    public function get()
    {
        return $this->incrementRank($this->prev);
    }

    /**
     * Increment the rank using a simple character incrementing logic.
     *
     * @param string $rank
     * @return string
     */
    private function incrementRank(string $rank): string
    {
        // If the rank is empty, start with 'a'
        if ($rank === '') {
            return 'a';
        }

        // Get the last character of the rank
        $lastChar = substr($rank, -1);

        // If the last character is less than 'z', increment it.
        if ($lastChar < 'z') {
            return substr($rank, 0, -1) . chr(ord($lastChar) + 1);
        }

        // If the last character is already 'z', append an 'a'
        return $rank . 'a';
    }
}
