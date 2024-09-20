<?php

namespace App\Services\Partition;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PartitionFactory
{
    public static function createInstancePartition(string $table, int $index_count): array
    {
        $tables = [];
        $db = new class($table) extends Model {

            use \App\Traits\DbPartition;

            protected string $baseTable;
            protected string $partition_prefix;

            public function __construct(string $table)
            {
                $this->baseTable = $table;
                $this->partition_prefix = $table;
            }
        };

        for ($i = 1; $i <= $index_count; $i++) {
            $tables[] =$db->createPartition();
        }

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
