<?php

namespace App\Enum;
use App\Contracts\SmartEnum;

enum MogouTypeEnum :int implements SmartEnum
{
    case MANGA = 0;
    case MANHWA = 1;
    case COMIC = 2;


    public static function getRandomMogouType(): int
    {
        return match (rand(0, 2)) {
            0 => self::MANGA->value,
            1 => self::MANHWA->value,
            2 => self::COMIC->value,
        };
    }

    public static function getMogouTypeName(MogouTypeEnum $type): string
    {
        return match ($type) {
            self::MANGA => 'Manga',
            self::MANHWA => 'Manhwa',
            self::COMIC => 'Comic',
        };
    }

    public static function getValues(): array
    {
        return [
            self::MANGA->value,
            self::MANHWA->value,
            self::COMIC->value,
        ];
    }

    public static function getMogouType(string $type): int
    {
        $type = strtolower($type);
        return match ($type) {
            'manga' => self::MANGA->value,
            'manhwa' => self::MANHWA->value,
            'comic' => self::COMIC->value,
        };
    }

    public static function requiredInValidationMessage(): string
    {
        return "Mogou type must be one of the following: " . implode(',', self::getValues());
    }



}
