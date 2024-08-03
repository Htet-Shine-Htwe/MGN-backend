<?php

namespace App\Enum;

use App\Contracts\SmartEnum;

enum SocialInfoType :string implements SmartEnum
{
    case Image = 'image';
    case ReferSocial = 'refer_social';

    public static function getValues(): array
    {
        return [
            self::Image,
            self::ReferSocial
        ];
    }

    public static function getKeys(): array
    {
        return [
            'image' => self::Image,
            'refer_social' => self::ReferSocial
        ];
    }

    public static function getKey(string $label): string
    {
        return match ($label) {
            'Image' => self::Image->value,
            'Refer Social' => self::ReferSocial->value,
        };
    }

    public static function requiredInValidationMessage(): string
    {
        return "Social info type must be one of the following: " . implode(',', self::getValues());
    }


}
