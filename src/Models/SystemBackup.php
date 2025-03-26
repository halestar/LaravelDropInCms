<?php

namespace halestar\LaravelDropInCms\Models;

use halestar\LaravelDropInCms\DiCMS;

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
        $data = json_decode($dataString, true);
        if(!isset($data['tables']) || empty($data['tables']))
            return false;
        foreach($data['tables'] as $table)
        {
            $tBackup = TableBackup::loadBackupData($table);
            if($tables === null || in_array($tBackup->table_name, $tables))
                $tBackup->restore();
        }
        return true;
    }
}
