<?php

namespace App\Vaildations;

use App\Enum\MogouFinishStatus;
use App\Rules\custom\InEnum;

class MogouValidation
{
    public static function finishStatus(): string
    {
        return InEnum::createRule(MogouFinishStatus::class);
    }

    public static function status(): string
    {
        return InEnum::createRule(\App\Enum\MogousStatus::class);
    }

    public static function mogouType(bool $nullable = false): string
    {
        return InEnum::createRule(\App\Enum\MogouTypeEnum::class, $nullable);
    }

    public static function invalidFinishStatusMessages(): mixed
    {
        return InEnum::createMessage(\App\Enum\MogouFinishStatus::class);
    }

    public static function invalidStatusMessages(): mixed
    {
        return InEnum::createMessage(\App\Enum\MogousStatus::class);
    }

    public static function invalidMogouTypeMessages(): mixed
    {
        return InEnum::createMessage(\App\Enum\MogouTypeEnum::class);
    }
}
