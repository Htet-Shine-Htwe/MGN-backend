<?php

namespace App\Traits;

use App\Services\Partition\TablePartition;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait DbPartition
{
    public static function dbConstructing(): void
    {
        $available_tables = TablePartition::availableRotationKey();

        foreach ($available_tables as $av) {
            $modelInstance = self::class;
            (new $modelInstance)->firstOrCreate($av . "_" . (new $modelInstance)->getTable());
        }
    }

    public function getCurrentPartition(): string
    {
        return $this->partition_prefix;
    }

    public function setCurrentPartition(string $partition): void
    {
        $this->partition_prefix = $partition;
    }

    public function firstOrCreate(string $table): void
    {
        $this->checkTablePartition($table) ? : $this->createPartition();
    }


    public function getPartition(string $rotation_key): string
    {
        return  $rotation_key . '_' . $this->partition_prefix;
    }

    /**
     * createPartition
     *
     * @return array<string>
     */
    public function createPartition(): array
    {
        $available_partitions = TablePartition::availableRotationKey();

        $created_partitions = [];
        foreach ($available_partitions as $partition) {
            $sql = $this->createPartitionTableConnectionQuery($partition);

            try {
                $success = DB::statement($sql);
                $created_partitions[] = $partition . "_" . $this->getTable();
            } catch (\Exception $e) {
                if (str_contains($e->getMessage(), 'already exists')) {
                    continue;
                } else {
                    throw $e;
                }
            }
        }
        return $created_partitions;
    }

    public function checkTablePartition(string $table):bool
    {
        return Schema::hasTable($table);
    }

    public function createPartitionTableConnectionQuery(string $partition): string
    {
        $table = $this->getTable();

        return match (config('database.default')) {
            'mysql' => "CREATE TABLE {$partition}_{$table} LIKE {$table}",
            'sqlite' => "CREATE TABLE {$partition}_{$table} AS SELECT * FROM {$table} WHERE 0",
            default => "CREATE TABLE {$partition}_{$table} LIKE {$table}",
        };

    }

    public function getCreatedPartitions(): array
    {
        $available_partitions = TablePartition::availableRotationKey();

        $created_partitions = [];
        foreach ($available_partitions as $partition) {
            if ($this->checkTablePartition($partition . "_" . $this->getTable())) {
                $created_partitions[] = $partition . "_" . $this->getTable();
            }
        }
        return $created_partitions;
    }

}
