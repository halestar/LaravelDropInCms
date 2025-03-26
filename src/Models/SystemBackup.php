<?php

namespace halestar\LaravelDropInCms\Models;

use halestar\LaravelDropInCms\DiCMS;
use Illuminate\Support\Facades\Schema;

class SystemBackup
{
    private array $tableBackups;
    private string $timestamp;

    public function __construct()
    {
        $this->tableBackups = [];
        foreach(DiCMS::getCoreBackupTables() as $table)
            $this->tableBackups = array_merge($this->tableBackups, $table::getBackupTables());
        foreach(config('dicms.plugins', []) as $plugin)
            foreach($plugin::getBackUpableTables() as $table)
                $this->tableBackups = array_merge($this->tableBackups, $table::getBackupTables());
        $this->timestamp = date('Y-m-d H:i:s');
    }

    public function getBackupData(): string
    {
        $data =
            [
                'timestamp' => $this->timestamp,
                'tables' => [],
            ];
        foreach($this->tableBackups as $table)
            $data['tables'][] = $table->getBackupData();

        return json_encode($data);
    }

    public static function restore($dataString, ?array $tables = null): bool
    {
        //try ecoding the data string
        $dataString = mb_convert_encoding($dataString, "UTF-8", mb_detect_encoding($dataString));
        $data = json_decode($dataString, true);
        if(!isset($data['tables']) || empty($data['tables']))
            return false;
        Schema::disableForeignKeyConstraints();
        foreach($data['tables'] as $table)
        {
            $tBackup = TableBackup::loadBackupData($table);
            if($tables === null || in_array($tBackup->table_name, $tables))
                $tBackup->restore();
        }
        Schema::enableForeignKeyConstraints();
        return true;
    }
}
