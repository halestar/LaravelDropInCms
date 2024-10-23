<?php

namespace halestar\LaravelDropInCms\Models;

use Illuminate\Support\Facades\DB;

final class TableBackup
{
    private array $table_data;
    public function __construct(public string $table_name, private array $table_fields, array $table_data = null)
    {
        $this->table_data = $table_data?? DB::table($this->table_name)->select($this->table_fields)->get()->toArray();
    }

    public function getBackupData(): string
    {
        return json_encode(
        [
            'table_name' => $this->table_name,
            'table_fields' => $this->table_fields,
            'table_data' => $this->table_data,
        ]);
    }

    public static function loadBackupData(string $backupData): TableBackup
    {
        $data = json_decode($backupData, true);
        return new TableBackup($data['table_name'],$data['table_fields'], $data['table_data']);
    }

    public function restore(): void
    {
        DB::table($this->table_name)->truncate();
        DB::table($this->table_name)->insert($this->table_data);
    }


}
