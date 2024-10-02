<?php

namespace App\Enum;

enum SocialInfoStatus : int
{
    case Active = 1;
    case Inactive = 0;

    public function isActive(): bool
    {
        return $this->value === self::Active;
    }

    public function isInactive(): bool
    {
        return $this->value === self::Inactive;
    }

    public function getLabel(): string
    {
        return match ($this->value) {
            self::Active => 'Active',
            self::Inactive => 'Inactive',
            default => 'Unknown',
        };
    }

}
