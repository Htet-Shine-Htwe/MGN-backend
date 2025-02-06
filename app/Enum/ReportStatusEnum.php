<?php

namespace App\Enum;

use App\Contracts\SmartEnum;

enum ReportStatusEnum :int implements SmartEnum
{
    case OPEN = 0;
    case RESOLVED = 1;
    case IN_PROGRESS = 2;

    public static function getValues(): array
    {
        return [
            self::OPEN,
            self::RESOLVED,
            self::IN_PROGRESS
        ];
    }

    public static function requiredInValidationMessage() : string{
        return "Report Status type must be one of the following: " . implode(',', self::getValues());
    }

    public static function getByLabel(string $label): int
    {
        return match (ucwords($label)){
            "Open" => self::OPEN->value,
            "Resolved" => self::RESOLVED->value,
            "In Progress" => self::IN_PROGRESS->value,
            default => throw new \InvalidArgumentException("". $label .""),
        };
    }

    public static function getByValue(int $value): string
    {
        return match ($value) {
            self::OPEN->value => "Open",
            self::RESOLVED->value => "Resolved",
            self::IN_PROGRESS->value => "In Progress",
            default => throw new \InvalidArgumentException("". $value .""),
        };
    }

}
