<?php

namespace App\Rules\custom;

use App\Contracts\SmartEnum;

class InEnum
{

    public static function getEnumValuesAsString(string $enumClass)
    {
        return implode(',', $enumClass::getValues());
    }

    public static function createRule(string $enumClass,bool $nullable = false)
    {
        return ($nullable ? 'nullable|' : 'required|') . 'in:' . self::getEnumValuesAsString($enumClass);
    }

    public static function createMessage(string $enumClass)
    {
        return $enumClass::requiredInValidationMessage();
    }



}
