<?php

namespace halestar\LaravelDropInCms\Controllers;

use halestar\LaravelDropInCms\Models\Site;
use halestar\LaravelDropInCms\Models\SystemBackup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;
use ZipStream\ZipStream;

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
	    return new StreamedResponse(function()
	    {
		    $backup = new SystemBackup();
		    $zip = new ZipStream
		    (
			    sendHttpHeaders: true,
			    outputName: 'dicms-backup' . date('Y-m-d-h-i-s') . '.zip',
		    );
		    $zip->addFile('backup.json', $backup->getBackupData());
		    $zip->finish();
	    });
    }

    public function restore(Request $request)
    {
        Gate::authorize('backup', Site::class);
        $request->validate(['restore_file' => 'required|file']);
        $file = $request->file('restore_file');
	    //is this a zip file?
	    if($file->getMimeType() == "application/zip")
	    {
		    $zip = new ZipArchive();
		    if($zip->open($file->getRealPath(), ZipArchive::RDONLY) === TRUE)
		    {
			    if(($backupData = $zip->getFromName('backup.json')) !== FALSE)
			    {
				    SystemBackup::restore($backupData);
			    }
		    }
	    }
	    elseif($file->getMimeType() == "application/json")
	    {
		    SystemBackup::restore(file_get_contents($file->getRealPath()));
	    }
        return redirect()->back();
    }
}
