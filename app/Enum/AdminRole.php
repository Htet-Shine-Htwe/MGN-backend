<?php

namespace App\Enum;
use App\Contracts\SmartEnum;

enum AdminRole : string implements SmartEnum
{
    case Admin = 'admin';
    case Uploader = 'uploader';

    public static function getRoles(): array
    {
        return [
            self::Admin,
            self::Uploader
        ];
    }

    public static function getValues(): array
    {
        return [
            'admin',
            'uploader'
        ];
    }

    public static function requiredInValidationMessage(): string
    {
        return "Role must be one of the following: " . implode(',', self::getValues());
    }
}


