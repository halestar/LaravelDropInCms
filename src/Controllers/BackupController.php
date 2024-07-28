<?php

namespace halestar\LaravelDropInCms\Controllers;

use halestar\LaravelDropInCms\Models\Site;
use halestar\LaravelDropInCms\Models\SystemBackup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BackupController
{
    public function index()
    {
        Gate::authorize('backup', Site::class);
        return view('dicms::backups.index');
    }

    public function export()
    {
        Gate::authorize('backup', Site::class);
        return response()->streamDownload(function ()
        {
            $backup = new SystemBackup();
            echo $backup->getBackupData();
        }, 'backup.json');
    }

    public function restore(Request $request)
    {
        Gate::authorize('backup', Site::class);
        $request->validate(['restore_file' => 'required|file']);
        $file = $request->file('restore_file');
        SystemBackup::restore(file_get_contents($file->getRealPath()));
        return redirect()->back();
    }
}
