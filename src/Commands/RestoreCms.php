<?php

namespace halestar\LaravelDropInCms\Commands;

use halestar\LaravelDropInCms\Models\SystemBackup;
use Illuminate\Console\Command;
use Symfony\Component\HttpFoundation\File\Exception\CannotWriteFileException;

class RestoreCms extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dicms:restore-cms {data?} {--file= : Load data from this file.} {--string: uses the input as a string, same as blank.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restores the DiCMS database as either through a string or a file. If no option is specified a string is read from the console.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $fname = $this->option('file');
        if($fname)
        {
            $file = fopen($fname, 'r');
            if(!$file)
            {
                $this->error(__('dicms::admin.commands.file.read.error'));
                $this->fail(new CannotWriteFileException(__('dicms::admin.commands.file.read.error') . ": " . $fname));
            }
            else
            {
                SystemBackup::restore(fgets($file));
            }
        }
        else
        {
            $data = $this->option('data');
            if(!$data)
            {
                $this->error(__('dicms::admin.commands.data.error'));
                $this->fail(new CannotWriteFileException(__('dicms::admin.commands.data.error') . ": " . $fname));
            }
            else
            {
                SystemBackup::restore($data);
            }
        }
    }
}
