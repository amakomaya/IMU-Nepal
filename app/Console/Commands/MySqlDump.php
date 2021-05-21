<?php

namespace App\Console\Commands;

use Symfony\Component\Process\Process;

use Illuminate\Console\Command;

class MySqlDump extends Command
{
    protected $signature = 'db:dump';

    protected $description = 'Backup the database';

    protected $process;

    public function __construct()
    {
        parent::__construct();

        $ds = DIRECTORY_SEPARATOR;

        $ts = time();

        $path = database_path() . $ds . 'backups' . $ds . date('Y', $ts) . $ds . date('m', $ts) . $ds . date('d', $ts) . $ds;
        $file = date('Y-m-d-His', $ts) . '-dump'.'.sql';


        $this->process = new Process(sprintf(
            'mysqldump -u %s -p%s %s > %s ',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            $path.$file
        ));

        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }

    public function handle()
    {
        $this->process->mustRun();
    }
}
