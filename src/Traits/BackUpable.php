<?php

namespace halestar\LaravelDropInCms\Traits;

use halestar\LaravelDropInCms\Models\TableBackup;
use Illuminate\Support\Facades\Schema;

trait BackUpable
{
    protected static function getTablesToBackup(): array { return []; }
    public static function getBackupTables(): array
    {
        $backups = [];
        foreach(static::getTablesToBackup() as $table_name)
            $backups[] = new TableBackup($table_name, Schema::getColumnListing($table_name));
        return $backups;
    }
}
