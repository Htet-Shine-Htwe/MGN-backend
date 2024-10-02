<?php

namespace App\Enum;
use App\Contracts\SmartEnum;

enum AdminRole : string implements SmartEnum
{
    case Admin = 'admin';
    case Moderator = 'moderator';

    public static function getRoles(): array
    {
        return [
            self::Admin,
            self::Moderator
        ];
    }

    public static function getValues(): array
    {
        return [
            'admin',
            'moderator'
        ];
    }

    public static function requiredInValidationMessage(): string
    {
        return "Role must be one of the following: " . implode(',', self::getValues());
    }
}


