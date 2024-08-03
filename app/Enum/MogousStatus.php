<?php

namespace App\Enum;
use App\Contracts\SmartEnum;

enum MogousStatus : int implements SmartEnum
{
    case DRAFT = 0;
    case PUBLISHED = 1;
    case ARCHIVED = 2;

    public static function getRandomStatus(): int
    {
        return match (rand(0, 2)) {
            0 => self::DRAFT->value,
            1 => self::PUBLISHED->value,
            2 => self::ARCHIVED->value,
        };
    }

    public static function getStatusName(MogousStatus $status): string
    {
        return match ($status) {
            self::DRAFT => "Draft",
            self::PUBLISHED => "Published",
            self::ARCHIVED => "Archived",
        };
    }

    public static function getValues(): array
    {
        return [
            self::DRAFT->value,
            self::PUBLISHED->value,
            self::ARCHIVED->value,
        ];
    }

    public static function getStatus(string $status): int
    {
        return match ($status) {
            "Draft" => self::DRAFT->value,
            "Published" => self::PUBLISHED->value,
            "Archived" => self::ARCHIVED->value,
        };
    }

    public static function requiredInValidationMessage(): string
    {
        return "Status must be one of the following: " . implode(',', self::getValues());
    }

}
