<?php

namespace App\Vaildations;

use App\Enum\MogouFinishStatus;
use App\Rules\custom\InEnum;

class MogouValidation
{
    public static function finishStatus()
    {
        return InEnum::createRule(MogouFinishStatus::class);
    }

    public static function status()
    {
        return InEnum::createRule(\App\Enum\MogousStatus::class);
    }

    public static function mogouType(bool $nullable = false)
    {
        return InEnum::createRule(\App\Enum\MogouTypeEnum::class, $nullable);
    }

    // messages
    public static function invalidFinishStatusMessages()
    {
        return InEnum::createMessage(\App\Enum\MogouFinishStatus::class);
    }

    public static function invalidStatusMessages()
    {
        return InEnum::createMessage(\App\Enum\MogousStatus::class);
    }

    public static function invalidMogouTypeMessages()
    {
        return InEnum::createMessage(\App\Enum\MogouTypeEnum::class);
    }
}
