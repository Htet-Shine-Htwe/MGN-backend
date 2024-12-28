<?php

namespace App\Contracts;

interface SmartEnum
{
    public static function getValues(): array;

    public static function requiredInValidationMessage() : string;
}
