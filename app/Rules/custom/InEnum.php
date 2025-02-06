<?php

namespace App\Rules\custom;

use App\Contracts\SmartEnum;

class InEnum
{

    public static function getEnumValuesAsString(string $enumClass): string
    {
        return implode(',', $enumClass::getValues());
    }

    public static function createRule(string $enumClass,bool $nullable = true): string
    {
        return ($nullable ? 'nullable|' : 'required|') . 'in:' . self::getEnumValuesAsString($enumClass);
    }

    public static function createMessage(string $enumClass): mixed
    {
        return $enumClass::requiredInValidationMessage();
    }



}
