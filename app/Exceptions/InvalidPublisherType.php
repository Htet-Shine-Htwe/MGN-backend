<?php

namespace App\Exceptions;

class InvalidPublisherType extends \Exception
{
    public function __construct($message = 'Publisher not found')
    {
        parent::__construct($message);
    }
}
