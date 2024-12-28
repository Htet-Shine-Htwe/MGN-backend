<?php

namespace App\Services\Partition;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PartitionFactory
{
     /**
     * Set the number of locked rotation keys.
     *
     * @return array<string>
     */
    public static function createInstancePartition(string $instanceClass, int $index_count): array
    {
        $tables = [];

        $db = new $instanceClass();

        if (!$db instanceof Model) {
            throw new InvalidArgumentException("Class must be an instance of Model.");
        }

        if (!in_array(\App\Traits\DbPartition::class, class_uses($db))) {
            throw new InvalidArgumentException("Class must use DbPartition trait.");
        }

        if (!method_exists($db, 'createPartition')) {
            throw new InvalidArgumentException("Method createPartition not found.");
        }

        $tables = $db->createPartition();

        return $tables;
    }

    public static function shareData(string $source_table, string $destination_table): void
    {
        $source_table_size = DB::table($source_table)->count();

        if ($source_table_size > 1000) {
            $data = DB::table($source_table)->get();
            $chunks = $data->chunk(1000);

            foreach ($chunks as $chunk) {
                DB::table($destination_table)->insert($chunk->toArray());
            }
        }
        else{
            DB::statement("insert into $destination_table select * from $source_table");
        }
    }
}
