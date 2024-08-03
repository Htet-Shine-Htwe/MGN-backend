<?php


namespace App\Contracts;

interface DbPartitionModelInterface
{


    public function createPartition(string $table, string $partitionName, string $partitionValue): void;
}
