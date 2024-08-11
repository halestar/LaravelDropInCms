<?php

namespace halestar\LaravelDropInCms\Commands;

use halestar\LaravelDropInCms\Models\SystemBackup;
use Illuminate\Console\Command;
use Symfony\Component\HttpFoundation\File\Exception\CannotWriteFileException;

class BackupCms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dicms:backup-cms {--file= : Use the file specified to load the site data.} {--string: Use the provided string to recover the site data..}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Back ups the DiCMS database as either a string or a file. If no option is specified a string is outputted to the console.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fname = $this->option('file');
        $backup = new SystemBackup();
        if($fname)
        {
            $file = fopen($fname, 'a');
            if(!$file)
            {
                $this->error(__('dicms::admin.commands.file.write.error'));
                $this->fail(new CannotWriteFileException(__('dicms::admin.commands.file.write.error') . ": " . $fname));
            }
            else
            {
                fputs($file, $backup->getBackupData());
            }
        }
        else
        {
            $this->line($backup->getBackupData());
        }
    }
}
