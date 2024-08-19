<?php

namespace App\Enum;

use App\Contracts\SmartEnum;

enum SocialMediaType :int implements SmartEnum {
    case Telegram = 1;


    public static function getValues(): array {
        return [
            self::Telegram,

        ];
    }

    public static function getKeys(): array {
        return [
            'Telegram' => self::Telegram,
        ];
    }

    public static function getKey( string $label ): int {
        return match ( $label ) {
            'Telegram' => self::Telegram->value,
        };
    }

    public static function requiredInValidationMessage(): string {
        return 'Social info type must be one of the following: ' . implode( ',', self::getValues() );
    }

}
