<?php

namespace App\Traits;

trait PartitionMap
{
    public function rMap(mixed $collection,string $table)
    {

        return $collection->map(
            function ($item) use ($table) {
                $item->setTable($table);
                return $item;
            }
        );
    }
}
