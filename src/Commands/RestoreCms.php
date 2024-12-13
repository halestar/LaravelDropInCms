<?php

namespace halestar\LaravelDropInCms\Commands;

use halestar\LaravelDropInCms\Models\SystemBackup;
use Illuminate\Console\Command;
use Symfony\Component\HttpFoundation\File\Exception\CannotWriteFileException;

class RestoreCms extends Command
{

    private ?string $data;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dicms:restore-cms {data?} {--file= : Load data from this file.} {--string: uses the input as a string, same as blank.} {--tables= : a comma-separated list of tables to import.}';

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
        $this->data = null;
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
                $this->data = fgets($file);
            }
        }
        else
        {
            $this->data = $this->option('data');
            if(!$this->data)
            {
                $this->error(__('dicms::admin.commands.data.error'));
                $this->fail(new CannotWriteFileException(__('dicms::admin.commands.data.error') . ": " . $fname));
            }
        }
        if($this->data)
        {
            $tables = $this->option('tables');
            $this->info("tables is " . print_r($tables, true));
            if($tables)
                $tables = explode(',', $tables);
            SystemBackup::restore($this->data, $tables);
        }
    }
}
