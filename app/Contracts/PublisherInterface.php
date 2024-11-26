<?php

namespace App\Contracts;

interface PublisherInterface
{
    /**
     * get the detail of the publisher
     * @return mixed
     */
    public function getPublisherDetail() : mixed;

    /**
     * status check of the bot id on related social provider
     *
     * @param string $id
     * @return bool
     */
    public function checkIsExistOnProvider(string $id) : bool;
}
