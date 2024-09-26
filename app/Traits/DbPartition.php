<?php

namespace App\Traits;

use App\Services\Partition\TablePartition;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait DbPartition
{

    public static function dbConstructing()
    {
        $available_tables = TablePartition::availableRotationKey();
        $table_name = (new self())->partition_prefix;

        foreach ($available_tables as $table) {
            (new self())->firstOrCreate($table."_".$table_name);
        }
    }

    public function getCurrentPartition()
    {
        return $this->partition_prefix;
    }

    public function firstOrCreate($table)
    {
        $this->checkTablePartition($table) ? : $this->createPartition();
    }

    public function getPartition(string $rotation_key): string
    {
        return  $rotation_key . '_' . $this->partition_prefix;
    }

    public function createPartition(): string | bool
    {
        $available_tables = TablePartition::availableRotationKey();
        $table_name = $this->partition_prefix;
        $base_table = $this->baseTable;

        foreach ($available_tables as $table) {
            $sql = $this->createPartitionTableConnectionQuery($table, $table_name, $base_table);

            try {
                DB::statement($sql);
                return $table . "_" . $table_name;
            } catch (\Exception $e) {
                if (str_contains($e->getMessage(), 'already exists')) {
                    continue;
                } else {
                    throw $e;
                }
            }
        }
        return false;
        // throw new \Exception("Only " . count($available_tables) . " rotations are allowed for partitioning");
    }



    public function checkTablePartition(string $table):bool
    {
        return Schema::hasTable($table);
    }

    public function createPartitionTableConnectionQuery($table, $table_name, $base_table)
    {
        $query = match (config('database.default')) {
            'mysql' => "CREATE TABLE  {$table}_{$table_name} LIKE {$base_table}",
            'sqlite' => "CREATE TABLE  {$table}_{$table_name} AS SELECT * FROM {$base_table} WHERE 0",
            default => "CREATE TABLE  {$table}_{$table_name} LIKE {$base_table}",
        };

        return $query;
    }

}
