<?php

namespace App\Enum;

use App\Contracts\SmartEnum;

enum SocialMediaType :int implements SmartEnum {
    case Telegram = 1;
    case Discord = 2;

    public static function getValues(): array {
        return [
            self::Telegram,
            self::Discord,
        ];
    }

    public static function getKeys(): array {
        return [
            self::Telegram->value => "Telegram",
            self::Discord->value => "Discord",
        ];
    }

    public static function getKey( SocialMediaType $value ): string {
        return match ( $value ) {
            self::Telegram => "Telegram",
            self::Discord => "Discord",
        };
    }

    public static function requiredInValidationMessage(): string {
        return 'Social info type must be one of the following: ' . implode( ',', self::getValues() );
    }

}
